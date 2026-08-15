<?php

namespace App\Console\Commands;

use App\Mail\AccountSuspended;
use App\Models\HostingAccount;
use App\Models\Invoice;
use App\Services\CpanelService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SuspendOverdueAccounts extends Command
{
    protected $signature   = 'skynetug:suspend-overdue';
    protected $description = 'Suspend hosting accounts whose invoice is overdue by more than 3 days';

    public function handle(CpanelService $cpanel): int
    {
        $this->info('Checking for overdue accounts...');

        $accounts = HostingAccount::where('status', 'active')
            ->where('next_due_date', '<=', now()->subDays(3)->toDateString())
            ->with('user')
            ->get();

        $suspended = 0;

        foreach ($accounts as $account) {
            $unpaid = Invoice::where('user_id', $account->user_id)
                ->whereIn('status', ['unpaid', 'overdue'])
                ->where('date_due', '<=', now()->subDays(3)->toDateString())
                ->exists();

            if ($unpaid) {
                $cpanel->suspendAccount($account->username, 'Invoice overdue');

                $account->update([
                    'status'             => 'suspended',
                    'suspended_at'       => now(),
                    'suspension_reason'  => 'Invoice overdue by more than 3 days',
                ]);

                // Mark invoice(s) as overdue
                Invoice::where('user_id', $account->user_id)
                    ->where('status', 'unpaid')
                    ->where('date_due', '<', now()->toDateString())
                    ->update(['status' => 'overdue']);

                // Notify customer
                Mail::to($account->user->email)->queue(new AccountSuspended($account));

                $suspended++;
                Log::info("Suspended overdue account: {$account->domain}");
            }
        }

        $this->info("Suspended {$suspended} overdue hosting accounts.");
        return Command::SUCCESS;
    }
}
