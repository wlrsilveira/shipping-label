<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    use HasFactory;

    protected $table = 'request_logs';

    protected $fillable = [
        'domain',
        'endpoint',
        'payload',
        'response',
        'status_code',
    ];

    protected $casts = [
        'payload' => 'array',
        'response' => 'array',
        'status_code' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
