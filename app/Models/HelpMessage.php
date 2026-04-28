<?php

namespace App\Models;

use App\Traits\ActivityDescriptionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class HelpMessage extends Model implements HasMedia
{
    use InteractsWithMedia;
    use LogsActivity;
    use ActivityDescriptionTrait;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => $this->descriptionForEvent($event, 'help message'))
        ;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
