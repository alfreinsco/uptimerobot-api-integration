<?php

namespace App\Exports;

use App\Models\Monitor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MonitorsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Monitor::query();

        // Apply filters same as API
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
            $query->where(function ($q) {
                $q->where('friendly_name', 'like', '%'.$this->filters['search'].'%')
                    ->orWhere('url', 'like', '%'.$this->filters['search'].'%');
            });
        }

        if (isset($this->filters['url_group']) && $this->filters['url_group'] !== 'Semua') {
            $query->where(function ($q) {
                $q->where('url', 'like', 'https://'.$this->filters['url_group'].'%')
                    ->orWhere('url', 'like', 'http://'.$this->filters['url_group'].'%')
                    ->orWhere('url', 'like', $this->filters['url_group'].'%');
            });
        }

        // Filter by date range (created_at)
        if (isset($this->filters['date_from']) && $this->filters['date_from']) {
            $query->whereDate('created_at', '>=', $this->filters['date_from']);
        }
        if (isset($this->filters['date_to']) && $this->filters['date_to']) {
            $query->whereDate('created_at', '<=', $this->filters['date_to']);
        }

        return $query->orderBy('id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Monitor ID',
            'Friendly Name',
            'URL',
            'Tipe',
            'Status',
            'Interval (detik)',
            'Timeout (detik)',
            'Created At',
            'Dibuat (UptimeRobot)',
        ];
    }

    /**
     * @param  Monitor  $monitor
     */
    public function map($monitor): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        $typeMap = [
            1 => 'HTTP(S)',
            2 => 'Keyword',
            3 => 'Ping',
            4 => 'Port',
        ];

        $statusMap = [
            0 => 'Paused',
            1 => 'Not Checked',
            2 => 'Up',
            8 => 'Seems Down',
            9 => 'Down',
        ];

        return [
            $rowNumber,
            $monitor->monitor_id ?? '-',
            $monitor->friendly_name ?? '-',
            $monitor->url ?? '-',
            $typeMap[$monitor->type] ?? 'Unknown',
            $statusMap[$monitor->status] ?? 'Unknown',
            $monitor->interval ?? 0,
            $monitor->timeout ?? 0,
            $monitor->created_at ? $monitor->created_at->format('Y-m-d H:i:s') : '-',
            $monitor->create_datetime ? $monitor->create_datetime->format('Y-m-d H:i:s') : '-',
        ];
    }
}
