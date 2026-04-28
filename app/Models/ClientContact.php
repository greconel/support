<?php

namespace App\Models;

use App\Http\Controllers\Ampp\ClientContacts\ShowClientContactController;
use App\Traits\ActivityDescriptionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ClientContact extends Model
{
    use LogsActivity;
    use LogsActivity;
    use ActivityDescriptionTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'tags' => 'array'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => $this->descriptionForEvent($event, 'client contact'))
        ;
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getFullNameWithEmailAttribute()
    {
        return trim("$this->first_name $this->last_name <$this->email>");
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }
}
