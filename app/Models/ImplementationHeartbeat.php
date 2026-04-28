<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImplementationHeartbeat extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'status_code' => 'integer',
        'response_time_ms' => 'integer',
        'success' => 'boolean',
        'created_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (self $heartbeat) {
            $heartbeat->created_at = $heartbeat->created_at ?? now();
        });
    }

    public function implementation(): BelongsTo
    {
        return $this->belongsTo(Implementation::class);
    }
}
