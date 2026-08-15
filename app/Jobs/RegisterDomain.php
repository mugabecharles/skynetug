<?php

namespace App\Jobs;

use App\Models\Domain;
use App\Models\Invoice;
use App\Models\User;
use App\Services\DomainRegistrarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegisterDomain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 60;

    public function __construct(
        public string  $domainName,
        public User    $user,
        public Invoice $invoice
    ) {}

    public function handle(DomainRegistrarService $registrar): void
    {
        // Extract SLD and TLD
        $parts = explode('.', $this->domainName, 2);
        $tld   = '.' . ($parts[1] ?? 'com');

        // Register via registrar API
        $result = $registrar->registerDomain($this->domainName, [
            'name'    => $this->user->name,
            'email'   => $this->user->email,
            'phone'   => $this->user->phone ?? '',
            'address' => $this->user->address ?? 'Kampala, Uganda',
            'country' => $this->user->country ?? 'UG',
        ]);

        if ($result['success']) {
            // Create domain record
            Domain::create([
                'user_id'           => $this->user->id,
                'order_id'          => $this->invoice->order_id,
                'domain_name'       => $this->domainName,
                'tld'               => $tld,
                'status'            => 'active',
                'registration_type' => 'register',
                'registration_date' => now()->toDateString(),
                'expiry_date'       => now()->addYear()->toDateString(),
                'registrar'         => 'skynetug',
                'registrar_id'      => $result['registrar_id'] ?? null,
                'auto_renew'        => true,
                'is_locked'         => true,
                'nameserver_1'      => 'ns1.skynetug.com',
                'nameserver_2'      => 'ns2.skynetug.com',
            ]);

            Log::info("Domain registered successfully: {$this->domainName} for {$this->user->email}");

            // Send confirmation email
            $this->sendConfirmationEmail();

        } else {
            Log::error("Domain registration failed for {$this->domainName}: " . ($result['error'] ?? 'unknown'));
            $this->fail(new \RuntimeException("Domain registration failed for {$this->domainName}"));
        }
    }

    protected function sendConfirmationEmail(): void
    {
        // Build a transient Domain model for the email
        $domain = \App\Models\Domain::where('domain_name', $this->domainName)
            ->where('user_id', $this->user->id)
            ->latest()
            ->first();

        if ($domain) {
            Mail::to($this->user->email)->queue(new \App\Mail\DomainRegistered($domain));
        }

        Log::info("Domain confirmation email queued for {$this->user->email} — domain: {$this->domainName}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("RegisterDomain job failed for {$this->domainName}: " . $exception->getMessage());
    }
}
