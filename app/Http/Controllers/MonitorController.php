<?php

namespace App\Http\Controllers;

use App\Models\Monitor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function index()
    {
        return view('monitor.index');
    }

    public function api(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);
        $status = $request->get('status');
        $type = $request->get('type');
        $search = $request->get('search');
        $urlGroup = $request->get('url_group');

        $query = Monitor::query();

        // Filter by status
        if ($status && $status !== 'Semua') {
            $statusMap = [
                'Online' => [2], // Up
                'Offline' => [8, 9], // Seems Down, Down
                'Paused' => [0], // Paused
            ];

            if (isset($statusMap[$status])) {
                $query->whereIn('status', $statusMap[$status]);
            }
        }

        // Filter by type
        if ($type && $type !== 'Semua') {
            $typeMap = [
                'HTTP(S)' => 1,
                'Keyword' => 2,
                'Ping' => 3,
                'Port' => 4,
            ];

            if (isset($typeMap[$type])) {
                $query->where('type', $typeMap[$type]);
            }
        }

        // Filter by search (friendly_name or url)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('friendly_name', 'like', '%'.$search.'%')
                    ->orWhere('url', 'like', '%'.$search.'%');
            });
        }

        // Filter by URL group (group by base URL domain)
        if ($urlGroup && $urlGroup !== 'Semua') {
            $query->where(function ($q) use ($urlGroup) {
                // Match domain in URL (after http:// or https://)
                $q->where('url', 'like', 'https://'.$urlGroup.'%')
                    ->orWhere('url', 'like', 'http://'.$urlGroup.'%')
                    ->orWhere('url', 'like', $urlGroup.'%');
            });
        }

        $monitors = $query->orderBy('id', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $monitors->items(),
            'current_page' => $monitors->currentPage(),
            'last_page' => $monitors->lastPage(),
            'per_page' => $monitors->perPage(),
            'total' => $monitors->total(),
            'has_more' => $monitors->hasMorePages(),
        ]);
    }

    public function urlGroups(): JsonResponse
    {
        // Get unique URL groups (grouped by domain)
        $monitors = Monitor::select('url')
            ->whereNotNull('url')
            ->where('url', '!=', '')
            ->distinct()
            ->get();

        $urlGroups = $monitors->map(function ($monitor) {
            $url = $monitor->url;
            if (preg_match('/https?:\/\/([^\/]+)/', $url, $matches)) {
                return $matches[1];
            }
            if (preg_match('/^([^\/]+)/', $url, $matches)) {
                return $matches[1];
            }

            return null;
        })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return response()->json([
            'data' => $urlGroups,
        ]);
    }

    public function export(Request $request)
    {
        $filters = [
            'status' => $request->get('status', 'Semua'),
            'type' => $request->get('type', 'Semua'),
            'search' => $request->get('search', ''),
            'url_group' => $request->get('url_group', 'Semua'),
        ];

        $filename = 'data-monitor-'.now()->format('Y-m-d-H').'.xlsx';

        $export = \App\Models\Export::create([
            'filename' => $filename,
            'file_path' => '',
            'filters' => $filters,
            'status' => 'pending',
        ]);

        \App\Jobs\ExportMonitorsJob::dispatch($export, $filters);

        return response()->json([
            'message' => 'Export sedang diproses. Silakan cek di halaman Exports.',
            'export_id' => $export->id,
        ]);
    }

    public function deleteByDateRange(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $deletedCount = Monitor::whereBetween('created_at', [$startDate, $endDate])->delete();

        return response()->json([
            'message' => "Berhasil menghapus {$deletedCount} data monitor",
            'deleted_count' => $deletedCount,
        ]);
    }
}
