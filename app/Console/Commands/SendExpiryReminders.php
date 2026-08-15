<?php

namespace App\Console\Commands;

use App\Mail\DomainExpiryReminder;
use App\Mail\PaymentReminder;
use App\Models\Domain;
use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendExpiryReminders extends Command
{
    protected $signature   = 'skynetug:expiry-reminders';
    protected $description = 'Send domain and invoice expiry reminder emails';

    public function handle(): int
    {
        $this->info('Sending expiry reminders...');

        // 7-day domain reminder
        $domains7 = Domain::where('status', 'active')
            ->whereBetween('expiry_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->whereNull('expiry_reminder_7_sent')
            ->with('user')
            ->get();

        foreach ($domains7 as $domain) {
            $domain->update(['expiry_reminder_7_sent' => now()]);
            $daysLeft = (int) now()->diffInDays($domain->expiry_date);
            Mail::to($domain->user->email)->queue(new DomainExpiryReminder($domain, $daysLeft));
            Log::info("7-day expiry reminder sent for domain {$domain->domain_name} → {$domain->user->email}");
        }

        // Late fee on invoices overdue 7+ days
        $overdueInvoices = Invoice::where('status', 'overdue')
            ->where('date_due', '<=', now()->subDays(7)->toDateString())
            ->whereNull('late_fee_applied_at')
            ->get();

        foreach ($overdueInvoices as $invoice) {
            $lateFee = config('billing.late_fee', 5000);
            $invoice->update([
                'late_fee'            => $lateFee,
                'total'               => $invoice->total + $lateFee,
                'late_fee_applied_at' => now(),
            ]);
            Log::info("Applied late fee to invoice {$invoice->invoice_number}");
        }

        // Payment reminders 3 days after due
        $reminders = Invoice::where('status', 'unpaid')
            ->where('date_due', '<=', now()->subDays(3)->toDateString())
            ->with('user')
            ->get();

        foreach ($reminders as $invoice) {
            Mail::to($invoice->user->email)->queue(new PaymentReminder($invoice));
            Log::info("Payment reminder sent for invoice {$invoice->invoice_number} → {$invoice->user->email}");
        }

        $this->info("Sent " . ($domains7->count() + $reminders->count()) . " notifications, applied {$overdueInvoices->count()} late fees.");
        return Command::SUCCESS;
    }
}
