<?php

namespace App\Models;

use App\Enums\TicketImpact;
use App\Enums\TicketSource;
use App\Enums\TicketStatus;
use App\Traits\ActivityDescriptionTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements HasMedia
{
    use HasFactory;
    use LogsActivity;
    use ActivityDescriptionTrait;
    use InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => TicketStatus::class,
        'impact' => TicketImpact::class,
        'source' => TicketSource::class,
        'ai_labelled_impact' => 'boolean',
        'ai_labelled_labels' => 'boolean',
        'closed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'assigned_to', 'impact', 'closed_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => $this->descriptionForEvent($event, 'ticket'))
        ;
    }

    public static function generateTicketNumber(): string
    {
        $last = static::orderByDesc('id')->first();

        $next = $last
            ? ((int) str_replace('#', '', $last->ticket_number)) + 1
            : 1;

        return '#' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to')->withTrashed();
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class)
            ->withPivot('ai_labelled')
            ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(TicketMessage::class)->orderBy('sent_at');
    }

    public function timeRegistrations(): HasMany
    {
        return $this->hasMany(TimeRegistration::class);
    }

    public function aiAnalyses(): HasMany
    {
        return $this->hasMany(AiAnalysis::class);
    }

    public function aiCorrectionLogs(): HasMany
    {
        return $this->hasMany(AiCorrectionLog::class);
    }
}
