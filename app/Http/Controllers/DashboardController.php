<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

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

        // Get total data records (all monitors, not just unique monitor_id)
        $totalData = Monitor::count();

        $stats = [
            'total' => $monitors->count(),
            'total_data' => $totalData,
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
            // Cache untuk 15 detik (sesuai dengan interval frontend yang lebih lama)
            $cacheKey = 'dashboard:stats';

            $data = Cache::remember($cacheKey, 15, function () {
                return $this->calculateStats();
            });

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
                    'total_data' => 0,
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
            // Cache untuk 30 detik (data 24 jam tidak berubah terlalu cepat)
            $cacheKey = 'dashboard:chart:24h:'.now()->format('Y-m-d-H-i');
            $data = Cache::remember($cacheKey, 30, function () {
                return $this->calculateChartData24Hours();
            });

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

    private function calculateChartData24Hours(): array
    {
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

        return $data;
    }

    public function chartDataWeek(): JsonResponse
    {
        try {
            // Cache untuk 5 menit (data 1 minggu tidak berubah terlalu cepat)
            $cacheKey = 'dashboard:chart:week:'.now()->format('Y-m-d-H');
            $data = Cache::remember($cacheKey, 300, function () {
                return $this->calculateChartDataWeek();
            });

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

    private function calculateChartDataWeek(): array
    {
        // Get data for last 7 days, grouped by day
        $days = 7;
        $now = now();
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $dayEnd = $now->copy()->subDays($i)->endOfDay();

            // Get latest status for each monitor_id up to this day
            $latestMonitorData = Monitor::selectRaw('monitor_id, MAX(created_at) as max_created_at')
                ->where('created_at', '<=', $dayEnd)
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

            $dayStart = $now->copy()->subDays($i)->startOfDay();
            $data[] = [
                'time' => $dayStart->format('d/m'),
                'datetime' => $dayStart->toIso8601String(),
                'stats' => $stats,
            ];
        }

        return $data;
    }

    public function chartDataMonth(): JsonResponse
    {
        try {
            // Cache untuk 10 menit (data 1 bulan tidak berubah terlalu cepat)
            $cacheKey = 'dashboard:chart:month:'.now()->format('Y-m-d');
            $data = Cache::remember($cacheKey, 600, function () {
                return $this->calculateChartDataMonth();
            });

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

    private function calculateChartDataMonth(): array
    {
        // Get data for last 4 weeks, grouped by week
        $weeks = 4;
        $now = now();
        $data = [];

        for ($i = $weeks - 1; $i >= 0; $i--) {
            $weekEnd = $now->copy()->subWeeks($i)->endOfWeek();

            // Get latest status for each monitor_id up to this week
            $latestMonitorData = Monitor::selectRaw('monitor_id, MAX(created_at) as max_created_at')
                ->where('created_at', '<=', $weekEnd)
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

            $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
            $data[] = [
                'time' => 'Minggu '.($weeks - $i),
                'datetime' => $weekStart->toIso8601String(),
                'stats' => $stats,
            ];
        }

        return $data;
    }

    public function chartDataYear(): JsonResponse
    {
        try {
            // Cache untuk 30 menit (data 1 tahun tidak berubah terlalu cepat)
            $cacheKey = 'dashboard:chart:year:'.now()->format('Y-m-d');
            $data = Cache::remember($cacheKey, 1800, function () {
                return $this->calculateChartDataYear();
            });

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

    private function calculateChartDataYear(): array
    {
        // Get data for last 12 months, grouped by month
        $months = 12;
        $now = now();
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $monthEnd = $now->copy()->subMonths($i)->endOfMonth();

            // Get latest status for each monitor_id up to this month
            $latestMonitorData = Monitor::selectRaw('monitor_id, MAX(created_at) as max_created_at')
                ->where('created_at', '<=', $monthEnd)
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

            $monthStart = $now->copy()->subMonths($i)->startOfMonth();
            $data[] = [
                'time' => $monthStart->format('M Y'),
                'datetime' => $monthStart->toIso8601String(),
                'stats' => $stats,
            ];
        }

        return $data;
    }
}
