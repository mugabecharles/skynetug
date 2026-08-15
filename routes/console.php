<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── SkyNetug Automated Tasks ──────────────────────────────────────────────────

// Suspend hosting accounts overdue > 3 days — runs every 6 hours
Schedule::command('skynetug:suspend-overdue')->everySixHours();

// Generate renewal invoices for services due in 14 days — runs daily at 8am
Schedule::command('skynetug:process-renewals')->dailyAt('08:00');

// Send expiry reminders and apply late fees — runs daily at 9am
Schedule::command('skynetug:expiry-reminders')->dailyAt('09:00');
