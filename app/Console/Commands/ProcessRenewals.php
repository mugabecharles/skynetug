<?php

namespace App\Console\Commands;

use App\Mail\DomainExpiryReminder;
use App\Mail\InvoiceGenerated;
use App\Models\HostingAccount;
use App\Models\Domain;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessRenewals extends Command
{
    protected $signature   = 'skynetug:process-renewals';
    protected $description = 'Generate renewal invoices for services due in 14 days';

    public function handle(): int
    {
        $this->info('Processing renewals...');
        $generated = 0;

        // Hosting renewals due in 14 days
        $accounts = HostingAccount::where('status', 'active')
            ->whereBetween('next_due_date', [now()->toDateString(), now()->addDays(14)->toDateString()])
            ->with(['user', 'hostingPackage'])
            ->get();

        foreach ($accounts as $account) {
            // Skip if renewal invoice already exists
            $exists = Invoice::where('user_id', $account->user_id)
                ->where('type', 'renewal')
                ->whereIn('status', ['unpaid', 'overdue'])
                ->whereHas('items', fn($q) => $q->where('description', 'like', "%{$account->domain}%"))
                ->exists();

            if (!$exists && $account->hostingPackage) {
                $price   = $account->hostingPackage->price_yearly;
                $invoice = Invoice::create([
                    'invoice_number' => Invoice::generateInvoiceNumber(),
                    'user_id'        => $account->user_id,
                    'status'         => 'unpaid',
                    'type'           => 'renewal',
                    'subtotal'       => $price,
                    'total'          => $price,
                    'currency'       => 'UGX',
                    'date_created'   => now()->toDateString(),
                    'date_due'       => now()->addDays(7)->toDateString(),
                ]);

                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'description' => "Hosting Renewal: {$account->domain} ({$account->hostingPackage->name}) — Yearly",
                    'amount'      => $price,
                    'quantity'    => 1,
                ]);

                // Email the renewal invoice
                Mail::to($account->user->email)->queue(new InvoiceGenerated($invoice));

                $generated++;
                Log::info("Generated renewal invoice for hosting: {$account->domain}");
            }
        }

        // Domain renewals due in 30 days — send 30-day expiry reminder
        $domains = Domain::where('status', 'active')
            ->whereBetween('expiry_date', [now()->toDateString(), now()->addDays(30)->toDateString()])
            ->whereNull('expiry_reminder_30_sent')
            ->with('user')
            ->get();

        foreach ($domains as $domain) {
            $domain->update(['expiry_reminder_30_sent' => now()]);
            $daysLeft = (int) now()->diffInDays($domain->expiry_date);
            Mail::to($domain->user->email)->queue(new DomainExpiryReminder($domain, $daysLeft));
            Log::info("Sent 30-day expiry reminder for domain: {$domain->domain_name} → {$domain->user->email}");
        }

        $this->info("Generated {$generated} renewal invoices, sent {$domains->count()} 30-day domain reminders.");
        return Command::SUCCESS;
    }
}
