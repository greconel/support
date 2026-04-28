<?php

namespace App\Models;

use App\Enums\ProjectCategory;
use App\Traits\ActivityDescriptionTrait;
use App\Traits\TimeConversionTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Project extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;
    use LogsActivity;
    use ActivityDescriptionTrait;
    use TimeConversionTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'category' => ProjectCategory::class,
    ];

    protected static function booted()
    {
        static::deleting(function (Project $project) {
            foreach ($project->todos as $todo) {
                $todo->delete();
            }

            foreach ($project->notes as $note) {
                $note->delete();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => $this->descriptionForEvent($event, 'project', 'started'))
        ;
    }

    public function getNameWithClientAttribute()
    {
        if (! $this->client_id){
            return $this->name;
        }

        if ($this->client->company){
            return trim("$this->name <{$this->client->full_name} - {$this->client->company}>");
        }

        return trim("$this->name <{$this->client->full_name}>");
    }

    public function getTotalHoursAttribute()
    {
        $seconds = $this->timeRegistrations()->sum('total_time_in_seconds');

        return $this->secondsToHoursAndMinutes($seconds);
    }

    public function scopeAccess(Builder $query, User $user)
    {
        return $query->get()->filter(fn(Project $project) => $user->can('view', $project));
    }

    public function scopeContributedBetween(Builder $query, User $user, Carbon $start, Carbon $end)
    {
        return $query
            ->withTrashed()
            ->whereHas('timeRegistrations', function (Builder $q) use($user, $start, $end){
                return $q
                    ->where('user_id', '=', $user->id)
                    ->whereBetween('start', [$start, $end]);
            });
    }

    public function scopeGeneral(Builder $query)
    {
        return $query
            ->where('project_id', '=', null)
            ->orWhere('is_general', '=', true);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps()->withTrashed();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)
            ->withDefault([
                'first_name' => null,
                'last_name' => null,
                'company' => null
            ])
            ->withTrashed()
        ;
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'morphable', 'model_type', 'model_id');
    }

    public function todos(): MorphMany
    {
        return $this->morphMany(Todo::class, 'morphable', 'model_type', 'model_id');
    }

    public function timeRegistrations(): HasMany
    {
        return $this->hasMany(TimeRegistration::class);
    }

    public function emails(): MorphMany
    {
        return $this->morphMany(Email::class, 'morphable', 'model_type', 'model_id');
    }

    public function links(): HasMany
    {
        return $this->hasMany(ProjectLink::class);
    }

    public function projectActivities(): HasMany
    {
        return $this->hasMany(ProjectActivity::class);
    }
}
