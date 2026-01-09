<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    protected $fillable = [
        'monitor_id',
        'friendly_name',
        'url',
        'type',
        'sub_type',
        'keyword_type',
        'keyword_case_type',
        'keyword_value',
        'http_username',
        'http_password',
        'port',
        'interval',
        'timeout',
        'status',
        'create_datetime',
    ];

    protected function casts(): array
    {
        return [
            'create_datetime' => 'datetime',
        ];
    }
}
