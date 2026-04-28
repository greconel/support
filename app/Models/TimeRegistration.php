<?php

namespace App\Models;

use App\Traits\ActivityDescriptionTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class TimeRegistration extends Model
{
    use LogsActivity;
    use ActivityDescriptionTrait;

    protected $guarded = ['id', 'user_id'];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime'
    ];

    protected static function booted()
    {
        static::saving(function (TimeRegistration $timeRegistration){
            // calculate total time in seconds
            if ($timeRegistration->start && $timeRegistration->end){
                $timeRegistration->total_time_in_seconds = $timeRegistration->start->diffInSeconds($timeRegistration->end, absolute: true);
            }
        });

        static::saved(function (TimeRegistration $timeRegistration){
            // end currently running time registration and save quietly
            $runningTimeRegistrations = $timeRegistration
                ->user
                ?->timeRegistrations()
                ->where('end', '=', null);

            if ($runningTimeRegistrations->count() >= 1 && ! $timeRegistration->end){
                $runningTimeRegistrations = $runningTimeRegistrations->whereKeyNot($timeRegistration->id)->get();

                foreach ($runningTimeRegistrations as $runningTimeRegistration) {
                    $runningTimeRegistration->end = now();
                    $runningTimeRegistration->total_time_in_seconds = $runningTimeRegistration->start->diffInSeconds($runningTimeRegistration->end, absolute: true);
                    $runningTimeRegistration->saveQuietly();
                }
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => $this->descriptionForEvent($event, 'time registration', 'started'))
        ;
    }

    public function getFullCalendarEventAttribute()
    {
        if ($this->project){
            $title = "<b>{$this->project->name}</b> - " . $this->description;
        }
        else{
            $title = strtoupper('<b>-- ' . __('general.none_model', ['model' => __('projects.project')]) . ' -- </b>') . $this->description;
        }

        return [
            'id' => $this->id,
            'start' => $this->start,
            'end' => $this->end,
            'title' => $title,
            'total_time_in_seconds' => $this->total_time_in_seconds
        ];
    }

    public function scopeGeneral(Builder $query)
    {
        return $query
            ->with('project')
            ->where(function (Builder $q){
                return $q->where('project_id', '=', null)
                    ->orWhereRelation('project', 'is_general', '=', true);
            });
    }

    public function scopeProjectOnly(Builder $query): Builder
    {
        return $query->whereNull('ticket_id');
    }

    public function scopeTicketOnly(Builder $query): Builder
    {
        return $query->whereNotNull('ticket_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class)->withTrashed();
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function projectClient(): HasOneThrough
    {
        return $this->hasOneThrough(
            Client::class,
            Project::class,
            'id',
            'id',
            'project_id',
            'client_id'
        );
    }

    public function projectActivity(): BelongsTo
    {
        return $this->belongsTo(ProjectActivity::class);
    }
}
