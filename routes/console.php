<?php

use App\Jobs\SyncUptimeRobotMonitors;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule monitor sync every minute
Schedule::call(function () {
    SyncUptimeRobotMonitors::dispatch();
})->name('sync-uptimerobot-monitors')->everyMinute()->withoutOverlapping();
