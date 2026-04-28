<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class ImplementationSchedule extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'last_started_at' => 'datetime',
        'last_finished_at' => 'datetime',
        'last_exit_code' => 'integer',
        'grace_period_minutes' => 'integer',
        'last_overdue_notified_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function (self $schedule) {
            if (empty($schedule->ping_token)) {
                $schedule->ping_token = Str::random(64);
            }
        });
    }

    public function implementation(): BelongsTo
    {
        return $this->belongsTo(Implementation::class);
    }

    public function executions(): HasMany
    {
        return $this->hasMany(ScheduleExecution::class);
    }

    public function latestExecution(): HasOne
    {
        return $this->hasOne(ScheduleExecution::class)->latestOfMany();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->is_active || !$this->last_finished_at) {
            return false;
        }

        $cron = new \Cron\CronExpression($this->expression);
        $nextDue = $cron->getNextRunDate($this->last_finished_at);

        $gracePeriod = $this->grace_period_minutes ?? config('beacon.schedule_grace_period', 5);

        return now()->greaterThan($nextDue->modify("+{$gracePeriod} minutes"));
    }
}
