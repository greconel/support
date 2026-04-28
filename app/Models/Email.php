<?php

namespace App\Models;

use App\Traits\ActivityDescriptionTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Email extends Model implements HasMedia
{
    use InteractsWithMedia;
    use LogsActivity;
    use ActivityDescriptionTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'to' => 'array',
        'cc' => 'array',
        'bcc' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => $this->descriptionForEvent($event, 'email'))
        ;
    }

    public function morphable()
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }
}
