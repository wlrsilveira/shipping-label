<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingLabel extends Model
{
    use HasFactory;

    protected $table = 'shipping_labels';

    protected $fillable = [
        'user_id',
        'from_address',
        'to_address',
        'package_data',
        'external_shipment_id',
        'provider',
        'label_url',
        'tracking_code',
        'status',
        'carrier',
        'service',
        'rate',
    ];

    protected $casts = [
        'from_address' => 'array',
        'to_address' => 'array',
        'package_data' => 'array',
        'rate' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

