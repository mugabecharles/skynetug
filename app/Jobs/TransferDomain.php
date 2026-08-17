<?php

namespace App\Jobs;

use App\Mail\DomainTransferInitiated;
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

class TransferDomain implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 60;

    public function __construct(
        public string  $domainName,
        public User    $user,
        public Invoice $invoice,
        public string  $eppCode,
    ) {}

    public function handle(DomainRegistrarService $registrar): void
    {
        $parts = explode('.', $this->domainName, 2);
        $tld   = '.' . ($parts[1] ?? 'com');

        $result = $registrar->transferDomain($this->domainName, $this->eppCode);

        if ($result['success']) {
            // Create a pending domain record — becomes active once transfer completes
            $domain = Domain::create([
                'user_id'           => $this->user->id,
                'order_id'          => $this->invoice->order_id,
                'domain_name'       => $this->domainName,
                'tld'               => $tld,
                'status'            => 'pending',
                'registration_type' => 'transfer',
                'registration_date' => now()->toDateString(),
                'expiry_date'       => now()->addYear()->toDateString(),
                'registrar'         => 'skynetug',
                'registrar_id'      => $result['registrar_id'] ?? null,
                'auto_renew'        => true,
                'is_locked'         => false,
                'nameserver_1'      => 'ns1.skynetug.com',
                'nameserver_2'      => 'ns2.skynetug.com',
            ]);

            Log::info("Domain transfer initiated: {$this->domainName} for {$this->user->email}");

            // Notify customer
            Mail::to($this->user->email)->queue(new DomainTransferInitiated($domain));

        } else {
            Log::error("Domain transfer failed for {$this->domainName}: " . ($result['error'] ?? 'unknown'));
            $this->fail(new \RuntimeException("Domain transfer failed for {$this->domainName}"));
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("TransferDomain job failed for {$this->domainName}: " . $exception->getMessage());
    }
}
