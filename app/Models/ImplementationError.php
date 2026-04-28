<?php

namespace App\Models;

use App\Enums\ErrorLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImplementationError extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'level' => ErrorLevel::class,
        'line' => 'integer',
        'context' => 'json',
        'occurred_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (self $error) {
            $error->created_at = $error->created_at ?? now();
        });
    }

    public function implementation(): BelongsTo
    {
        return $this->belongsTo(Implementation::class);
    }
}
