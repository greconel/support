<?php

namespace App\Models;

use App\Traits\ActivityDescriptionTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Todo extends Model
{
    use LogsActivity;
    use ActivityDescriptionTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'end_date' => 'datetime'
    ];

    protected static $recordEvents = ['created'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => $this->descriptionForEvent($event, 'to do', 'added'))
        ;
    }

    public function scopeFinished(Builder $query): Builder
    {
        return $query->where('finished', '=', true);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->end_date < now();
    }

    public function morphable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
