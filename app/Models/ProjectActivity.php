<?php

namespace App\Models;

use App\Traits\TimeConversionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectActivity extends Model
{
    use TimeConversionTrait;

    protected $guarded = ['id'];

    public function getActualHoursAttribute()
    {
        $seconds = $this->timeRegistrations->sum('total_time_in_seconds');

        return $this->secondsToHours($seconds);
    }

    public function getActualHoursFloatAttribute()
    {
        $seconds = $this->timeRegistrations->sum('total_time_in_seconds');

        return $this->secondsToHoursFloat($seconds);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function timeRegistrations(): HasMany
    {
        return $this->hasMany(TimeRegistration::class);
    }
}
