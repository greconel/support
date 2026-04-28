<?php

namespace App\Models;

use App\Enums\ScheduleExecutionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleExecution extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'duration_ms' => 'integer',
        'exit_code' => 'integer',
        'status' => ScheduleExecutionStatus::class,
        'created_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (self $execution) {
            $execution->created_at = $execution->created_at ?? now();
        });
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(ImplementationSchedule::class, 'implementation_schedule_id');
    }
}
