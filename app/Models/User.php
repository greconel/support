<?php

namespace App\Models;

use App\Http\Controllers\Media\ShowMediaController;
use App\Traits\ActivityDescriptionTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use LogsActivity;
    use CausesActivity;
    use HasApiTokens;
    use InteractsWithMedia;
    use SoftDeletes;
    use ActivityDescriptionTrait;

    protected $fillable = [
        'name',
        'email',
        'password',
        'motion_user_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_active_at' => 'datetime'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => $this->descriptionForEvent($event, 'user'))
        ;
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->onlyTrashed();
    }

    public function getProfilePhotoUrlAttribute(){
        if ($this->getFirstMedia('avatar')){
            return action(ShowMediaController::class, $this->getFirstMedia('avatar'));
        }

        return Cache::rememberForever("avatar_$this->id", fn() => "https://eu.ui-avatars.com/api/?size=100&name=$this->name") ;
    }

    public function loginLogs(): HasMany
    {
        return $this->hasMany(LoginLog::class);
    }

    public function helpMessages(): HasMany
    {
        return $this->hasMany(HelpMessage::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)
            ->withTimestamps()
            ->withTrashed();
    }

    public function projectsWithoutArchive(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)
            ->withTimestamps();
    }

    public function timeRegistrations(): HasMany
    {
        return $this->hasMany(TimeRegistration::class);
    }

    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class);
    }

    public function assignedTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }
}
