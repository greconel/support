<?php

namespace App\Models;

use App\Traits\ActivityDescriptionTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Release extends Model
{
    use LogsActivity;
    use ActivityDescriptionTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'released_at' => 'datetime'
    ];

    public function scopeCurrentRelease(Builder $query)
    {
        return $query->firstWhere('is_current_release', '=', true);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => $this->descriptionForEvent($event, 'Release'))
        ;
    }
}
