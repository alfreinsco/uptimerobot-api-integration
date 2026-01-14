<?php

namespace App\Jobs;

use App\Models\Monitor;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncUptimeRobotMonitors implements ShouldBeUnique, ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = [60, 300];

    /**
     * The number of seconds the job can run before timing out.
     */
    public $timeout = 60;

    /**
     * The unique ID of the job.
     */
    public function uniqueId(): string
    {
        return 'sync-uptimerobot-monitors';
    }

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $apiKey = config('services.uptimerobot.api_key');

        if (! $apiKey) {
            Log::error('UptimeRobot API key not configured');

            return;
        }

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::timeout(30)->asForm()->post('https://api.uptimerobot.com/v2/getMonitors', [
                'api_key' => $apiKey,
                'format' => 'json',
            ]);

            if ($response->failed()) {
                Log::error('UptimeRobot API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \Exception('UptimeRobot API request failed with status: '.$response->status());
            }

            $data = $response->json();

            if (! isset($data['stat']) || $data['stat'] !== 'ok') {
                $errorType = $data['error']['type'] ?? null;
                $errorMessage = $data['error']['message'] ?? 'Unknown error';

                // Check if error is permanent (like invalid API key)
                $isPermanentError = in_array($errorType, ['invalid_parameter', 'auth_fail']);

                if ($isPermanentError) {
                    Log::error('UptimeRobot API permanent error - stopping retries', [
                        'error_type' => $errorType,
                        'error_message' => $errorMessage,
                        'data' => $data,
                    ]);

                    // Don't retry for permanent errors - let the schedule handle next attempt
                    return;
                }

                Log::warning('UptimeRobot API returned non-ok status', ['data' => $data]);

                throw new \Exception('UptimeRobot API returned non-ok status: '.$errorMessage);
            }

            if (! isset($data['monitors']) || ! is_array($data['monitors'])) {
                Log::warning('UptimeRobot API response missing monitors array', ['data' => $data]);

                throw new \Exception('UptimeRobot API response missing monitors array');
            }

            $createdCount = 0;

            foreach ($data['monitors'] as $monitorData) {
                try {
                    // Always create new record, never update (monitor_id can have duplicates)
                    Monitor::create($this->mapMonitorData($monitorData));
                    $createdCount++;
                } catch (\Exception $e) {
                    Log::error('Error creating monitor', [
                        'monitor_id' => $monitorData['id'] ?? 'unknown',
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    // Continue with next monitor even if one fails
                }
            }

            Log::info('UptimeRobot monitors synced', [
                'created' => $createdCount,
                'total' => count($data['monitors']),
            ]);

            // Hapus data yang sudah lebih dari 1 hari
            $deletedCount = $this->deleteOldMonitors();

            if ($deletedCount > 0) {
                Log::info('Old monitors deleted', [
                    'deleted' => $deletedCount,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error syncing UptimeRobot monitors', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Delete monitors older than 1 day
     */
    private function deleteOldMonitors(): int
    {
        try {
            $oneDayAgo = now()->subDay();

            return Monitor::where('created_at', '<', $oneDayAgo)->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting old monitors', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return 0;
        }
    }

    /**
     * Map API data to database format
     */
    private function mapMonitorData(array $data): array
    {
        return [
            'monitor_id' => $data['id'],
            'friendly_name' => $data['friendly_name'] ?? '',
            'url' => $data['url'] ?? '',
            'type' => $data['type'] ?? 1,
            'sub_type' => $data['sub_type'] ?? null,
            'keyword_type' => $data['keyword_type'] ?? 0,
            'keyword_case_type' => $data['keyword_case_type'] ?? 0,
            'keyword_value' => $data['keyword_value'] ?? null,
            'http_username' => $data['http_username'] ?? null,
            'http_password' => $data['http_password'] ?? null,
            'port' => $data['port'] ?? null,
            'interval' => $data['interval'] ?? 300,
            'timeout' => $data['timeout'] ?? 30,
            'status' => $data['status'] ?? 1,
            'create_datetime' => isset($data['create_datetime']) ? now()->setTimestamp($data['create_datetime']) : now(),
        ];
    }
}
