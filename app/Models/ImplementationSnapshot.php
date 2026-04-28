<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImplementationSnapshot extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'data' => 'json',
        'created_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (self $snapshot) {
            $snapshot->created_at = $snapshot->created_at ?? now();
        });
    }

    public function implementation(): BelongsTo
    {
        return $this->belongsTo(Implementation::class);
    }
}
