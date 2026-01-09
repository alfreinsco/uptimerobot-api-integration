<?php

namespace App\Console\Commands;

use App\Jobs\SyncUptimeRobotMonitors;
use Illuminate\Console\Command;

class SyncMonitorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monitors:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync monitors from UptimeRobot API every 10 seconds';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting monitor sync...');

        // Dispatch the job immediately
        SyncUptimeRobotMonitors::dispatch();

        $this->info('Monitor sync job dispatched. Job will automatically schedule next sync in 10 seconds.');
        $this->info('Make sure queue worker is running: php artisan queue:work');

        return Command::SUCCESS;
    }
}
