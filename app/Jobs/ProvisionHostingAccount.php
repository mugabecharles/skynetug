<?php

namespace App\Jobs;

use App\Models\HostingAccount;
use App\Models\Invoice;
use App\Models\OrderItem;
use App\Services\CpanelService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProvisionHostingAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 120;

    public function __construct(public Invoice $invoice) {}

    public function handle(CpanelService $cpanel): void
    {
        $order = $this->invoice->order;

        if (!$order) {
            Log::warning("ProvisionHostingAccount: Invoice {$this->invoice->id} has no order.");
            return;
        }

        $hostingItems = $order->orderItems->where('item_type', 'hosting');

        foreach ($hostingItems as $item) {
            $domain   = $item->meta['domain'] ?? null;
            $username = $this->generateUsername($domain);

            if (!$domain) {
                Log::warning("ProvisionHostingAccount: No domain for order item {$item->id}.");
                continue;
            }

            $rawPassword = Str::random(12) . '!1A';

            // Create the hosting account record
            $account = HostingAccount::create([
                'user_id'            => $this->invoice->user_id,
                'order_id'           => $order->id,
                'hosting_package_id' => $item->hosting_package_id,
                'domain'             => $domain,
                'username'           => $username,
                'cpanel_password'    => $rawPassword,
                'status'             => 'pending',
                'billing_cycle'      => $item->billing_cycle,
                'price'              => $item->unit_price,
                'registration_date'  => now()->toDateString(),
                'next_due_date'      => $item->service_end,
            ]);

            // Stash the raw password on the model (not persisted) for the credentials email
            $account->raw_password = $rawPassword;

            // Provision on cPanel
            $result = $cpanel->createAccount($account);

            if ($result['success']) {
                $account->update([
                    'status'           => 'active',
                    'cpanel_created_at'=> now(),
                    'cpanel_url'       => "https://{$account->server?->hostname}:2083",
                ]);

                // Send credentials email
                $this->sendCredentialsEmail($account);

                Log::info("Provisioned hosting account: {$domain} ({$username})");
            } else {
                Log::error("Failed to provision cPanel account for {$domain}: " . ($result['error'] ?? 'unknown'));
                // Retry handled by job tries
                $this->fail(new \RuntimeException("cPanel provisioning failed for {$domain}"));
            }
        }
    }

    private function generateUsername(string $domain): string
    {
        $base = preg_replace('/[^a-z0-9]/', '', strtolower(explode('.', $domain)[0]));
        $base = substr($base, 0, 8);

        $username = $base;
        $counter  = 1;

        while (HostingAccount::where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }

        return $username;
    }

    private function sendCredentialsEmail(HostingAccount $account): void
    {
        // The plain-text password was stored before hashing; we pass it via the job constructor
        // so we read it from the account's temporarily set raw_password property if available,
        // otherwise fall back to a reset prompt.
        $password = property_exists($account, 'raw_password') && $account->raw_password
            ? $account->raw_password
            : '(Please reset via cPanel login page)';

        Mail::to($account->user->email)
            ->queue(new \App\Mail\HostingCredentials($account, $password));

        Log::info("Credentials email queued for {$account->user->email} — account: {$account->domain}");
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("ProvisionHostingAccount job failed for invoice {$this->invoice->id}: " . $exception->getMessage());
    }
}
