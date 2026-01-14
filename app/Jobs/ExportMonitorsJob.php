<?php

namespace App\Jobs;

use App\Exports\MonitorsExport;
use App\Models\Export;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Maatwebsite\Excel\Facades\Excel;

class ExportMonitorsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Export $export,
        public array $filters
    ) {}

    public function handle(): void
    {
        try {
            $this->export->update(['status' => 'processing']);

            $filename = $this->export->filename;
            $filePath = 'exports/'.$filename;

            Excel::store(new MonitorsExport($this->filters), $filePath, 'local');

            $query = \App\Models\Monitor::query();

            if (isset($this->filters['status']) && $this->filters['status'] !== 'Semua') {
                $statusMap = [
                    'Online' => [2],
                    'Offline' => [8, 9],
                    'Paused' => [0],
                ];
                if (isset($statusMap[$this->filters['status']])) {
                    $query->whereIn('status', $statusMap[$this->filters['status']]);
                }
            }

            if (isset($this->filters['type']) && $this->filters['type'] !== 'Semua') {
                $typeMap = [
                    'HTTP(S)' => 1,
                    'Keyword' => 2,
                    'Ping' => 3,
                    'Port' => 4,
                ];
                if (isset($typeMap[$this->filters['type']])) {
                    $query->where('type', $typeMap[$this->filters['type']]);
                }
            }

            if (isset($this->filters['search']) && $this->filters['search']) {
                $search = $this->filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('friendly_name', 'like', '%'.$search.'%')
                        ->orWhere('url', 'like', '%'.$search.'%');
                });
            }

            if (isset($this->filters['url_group']) && $this->filters['url_group'] !== 'Semua') {
                $urlGroup = $this->filters['url_group'];
                $query->where(function ($q) use ($urlGroup) {
                    $q->where('url', 'like', 'https://'.$urlGroup.'%')
                        ->orWhere('url', 'like', 'http://'.$urlGroup.'%')
                        ->orWhere('url', 'like', $urlGroup.'%');
                });
            }

            // Filter by date range (created_at)
            if (isset($this->filters['date_from']) && $this->filters['date_from']) {
                $query->whereDate('created_at', '>=', $this->filters['date_from']);
            }
            if (isset($this->filters['date_to']) && $this->filters['date_to']) {
                $query->whereDate('created_at', '<=', $this->filters['date_to']);
            }

            $totalRecords = $query->count();

            $this->export->update([
                'status' => 'completed',
                'file_path' => $filePath,
                'total_records' => $totalRecords,
            ]);
        } catch (\Exception $e) {
            $this->export->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
