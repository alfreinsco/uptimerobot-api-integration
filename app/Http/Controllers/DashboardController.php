<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    private function calculateStats()
    {
        // Get latest monitor for each monitor_id (most recent status)
        // Use subquery to find max created_at for each monitor_id, then join to get full records
        $latestMonitorData = Monitor::selectRaw('monitor_id, MAX(created_at) as max_created_at')
            ->groupBy('monitor_id')
            ->get();

        $monitors = collect();

        foreach ($latestMonitorData as $data) {
            $monitor = Monitor::where('monitor_id', $data->monitor_id)
                ->where('created_at', $data->max_created_at)
                ->orderByDesc('id')
                ->first();

            if ($monitor) {
                $monitors->push($monitor);
            }
        }

        $stats = [
            'total' => $monitors->count(),
            'up' => 0,
            'down' => 0,
            'paused' => 0,
            'not_checked' => 0,
        ];

        foreach ($monitors as $monitor) {
            match ($monitor->status) {
                0 => $stats['paused']++,
                1 => $stats['not_checked']++,
                2 => $stats['up']++,
                8, 9 => $stats['down']++,
                default => null,
            };
        }

        return [
            'stats' => $stats,
            'monitors' => $monitors->sortByDesc('created_at')->take(10)->values()->map(function ($monitor) {
                return [
                    'id' => $monitor->id,
                    'monitor_id' => $monitor->monitor_id,
                    'friendly_name' => $monitor->friendly_name,
                    'url' => $monitor->url,
                    'type' => $monitor->type,
                    'status' => $monitor->status,
                    'create_datetime' => $monitor->create_datetime?->toIso8601String(),
                    'created_at' => $monitor->created_at?->toIso8601String(),
                ];
            }),
        ];
    }

    public function index()
    {
        $data = $this->calculateStats();

        return view('dashboard.index', [
            'monitors' => $data['monitors']->toArray(),
            'stats' => $data['stats'],
        ]);
    }

    public function api(): JsonResponse
    {
        try {
            $data = $this->calculateStats();

            return response()->json([
                'success' => true,
                'stats' => $data['stats'],
                'monitors' => $data['monitors'],
                'timestamp' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'stats' => [
                    'total' => 0,
                    'up' => 0,
                    'down' => 0,
                    'paused' => 0,
                    'not_checked' => 0,
                ],
                'monitors' => [],
            ], 500);
        }
    }

    public function chartData(): JsonResponse
    {
        try {
            // Get data for last 24 hours, grouped by hour
            $hours = 24;
            $now = now();
            $data = [];

            for ($i = $hours - 1; $i >= 0; $i--) {
                $hourEnd = $now->copy()->subHours($i)->endOfHour();

                // Get latest status for each monitor_id up to this hour
                $latestMonitorData = Monitor::selectRaw('monitor_id, MAX(created_at) as max_created_at')
                    ->where('created_at', '<=', $hourEnd)
                    ->groupBy('monitor_id')
                    ->get();

                $monitors = collect();
                foreach ($latestMonitorData as $monitorData) {
                    $monitor = Monitor::where('monitor_id', $monitorData->monitor_id)
                        ->where('created_at', $monitorData->max_created_at)
                        ->orderByDesc('id')
                        ->first();

                    if ($monitor) {
                        $monitors->push($monitor);
                    }
                }

                $stats = [
                    'up' => 0,
                    'down' => 0,
                    'paused' => 0,
                    'not_checked' => 0,
                ];

                foreach ($monitors as $monitor) {
                    match ($monitor->status) {
                        0 => $stats['paused']++,
                        1 => $stats['not_checked']++,
                        2 => $stats['up']++,
                        8, 9 => $stats['down']++,
                        default => null,
                    };
                }

                $hourStart = $now->copy()->subHours($i)->startOfHour();
                $data[] = [
                    'time' => $hourStart->format('H:i'),
                    'datetime' => $hourStart->toIso8601String(),
                    'stats' => $stats,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    public function chartData1Hour(): JsonResponse
    {
        try {
            // Get data for last 1 hour, grouped by 5 minutes
            $minutes = 60;
            $interval = 5; // 5 minutes interval
            $now = now();
            $data = [];

            for ($i = $minutes - $interval; $i >= 0; $i -= $interval) {
                $minuteEnd = $now->copy()->subMinutes($i)->endOfMinute();

                // Get latest status for each monitor_id up to this minute
                $latestMonitorData = Monitor::selectRaw('monitor_id, MAX(created_at) as max_created_at')
                    ->where('created_at', '<=', $minuteEnd)
                    ->groupBy('monitor_id')
                    ->get();

                $monitors = collect();
                foreach ($latestMonitorData as $monitorData) {
                    $monitor = Monitor::where('monitor_id', $monitorData->monitor_id)
                        ->where('created_at', $monitorData->max_created_at)
                        ->orderByDesc('id')
                        ->first();

                    if ($monitor) {
                        $monitors->push($monitor);
                    }
                }

                $stats = [
                    'up' => 0,
                    'down' => 0,
                    'paused' => 0,
                    'not_checked' => 0,
                ];

                foreach ($monitors as $monitor) {
                    match ($monitor->status) {
                        0 => $stats['paused']++,
                        1 => $stats['not_checked']++,
                        2 => $stats['up']++,
                        8, 9 => $stats['down']++,
                        default => null,
                    };
                }

                $minuteStart = $now->copy()->subMinutes($i)->startOfMinute();
                $data[] = [
                    'time' => $minuteStart->format('H:i'),
                    'datetime' => $minuteStart->toIso8601String(),
                    'stats' => $stats,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    public function chartData1Minute(): JsonResponse
    {
        try {
            // Get data for last 1 minute, grouped by 10 seconds
            $seconds = 60;
            $interval = 10; // 10 seconds interval
            $now = now();
            $data = [];

            for ($i = $seconds - $interval; $i >= 0; $i -= $interval) {
                $secondEnd = $now->copy()->subSeconds($i)->endOfSecond();

                // Get latest status for each monitor_id up to this second
                $latestMonitorData = Monitor::selectRaw('monitor_id, MAX(created_at) as max_created_at')
                    ->where('created_at', '<=', $secondEnd)
                    ->groupBy('monitor_id')
                    ->get();

                $monitors = collect();
                foreach ($latestMonitorData as $monitorData) {
                    $monitor = Monitor::where('monitor_id', $monitorData->monitor_id)
                        ->where('created_at', $monitorData->max_created_at)
                        ->orderByDesc('id')
                        ->first();

                    if ($monitor) {
                        $monitors->push($monitor);
                    }
                }

                $stats = [
                    'up' => 0,
                    'down' => 0,
                    'paused' => 0,
                    'not_checked' => 0,
                ];

                foreach ($monitors as $monitor) {
                    match ($monitor->status) {
                        0 => $stats['paused']++,
                        1 => $stats['not_checked']++,
                        2 => $stats['up']++,
                        8, 9 => $stats['down']++,
                        default => null,
                    };
                }

                $secondStart = $now->copy()->subSeconds($i)->startOfSecond();
                $data[] = [
                    'time' => $secondStart->format('H:i:s'),
                    'datetime' => $secondStart->toIso8601String(),
                    'stats' => $stats,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }
}
