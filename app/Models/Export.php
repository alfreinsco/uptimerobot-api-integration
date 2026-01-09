<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    protected $fillable = [
        'filename',
        'file_path',
        'filters',
        'total_records',
        'status',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'array',
            'total_records' => 'integer',
        ];
    }
}
