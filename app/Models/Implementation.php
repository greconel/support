<?php

namespace App\Models;

use App\Enums\ImplementationStatus;
use App\Enums\ImplementationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Implementation extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => ImplementationType::class,
        'status' => ImplementationStatus::class,
        'tags' => 'json',
        'heartbeat_interval' => 'integer',
        'last_heartbeat_at' => 'datetime',
        'last_push_at' => 'datetime',
    ];

    public function beaconKey(): HasOne
    {
        return $this->hasOne(BeaconKey::class);
    }

    public function snapshots(): HasMany
    {
        return $this->hasMany(ImplementationSnapshot::class);
    }

    public function heartbeats(): HasMany
    {
        return $this->hasMany(ImplementationHeartbeat::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ImplementationSchedule::class);
    }

    public function errors(): HasMany
    {
        return $this->hasMany(ImplementationError::class);
    }

    public function latestSnapshot(): HasOne
    {
        return $this->hasOne(ImplementationSnapshot::class)->latestOfMany();
    }

    public function getUptimePercentageAttribute(): ?float
    {
        $total = $this->heartbeats()->where('created_at', '>=', now()->subDay())->count();

        if ($total === 0) {
            return null;
        }

        $successful = $this->heartbeats()
            ->where('created_at', '>=', now()->subDay())
            ->where('success', true)
            ->count();

        return round(($successful / $total) * 100, 2);
    }

    public function getLatestSnapshotDataAttribute(): array
    {
        return $this->latestSnapshot?->data ?? [];
    }

    public function scopeOnline($query)
    {
        return $query->where('status', ImplementationStatus::Online);
    }

    public function scopeOffline($query)
    {
        return $query->where('status', ImplementationStatus::Offline);
    }

    public function scopeBeacon($query)
    {
        return $query->where('type', ImplementationType::Beacon);
    }

    public function scopeManual($query)
    {
        return $query->where('type', ImplementationType::Manual);
    }
}
