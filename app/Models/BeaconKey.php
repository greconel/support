<?php

namespace App\Models;

use App\Enums\BeaconKeyStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class BeaconKey extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => BeaconKeyStatus::class,
    ];

    protected $hidden = ['key'];

    public function implementation(): BelongsTo
    {
        return $this->belongsTo(Implementation::class);
    }

    public static function generate(?string $label = null, string $prefix = 'bk_live_'): self
    {
        return self::create([
            'key' => $prefix . Str::random(64),
            'label' => $label,
            'status' => BeaconKeyStatus::Unclaimed,
        ]);
    }

    public function claim(Implementation $implementation): void
    {
        $this->update([
            'status' => BeaconKeyStatus::Claimed,
            'implementation_id' => $implementation->id,
        ]);
    }

    public function disable(): void
    {
        $this->update(['status' => BeaconKeyStatus::Disabled]);
    }

    public function getMaskedKeyAttribute(): string
    {
        return substr($this->key, 0, 12) . str_repeat('*', 20) . substr($this->key, -6);
    }

    public function scopeUnclaimed($query)
    {
        return $query->where('status', BeaconKeyStatus::Unclaimed);
    }

    public function scopeActive($query)
    {
        return $query->where('status', BeaconKeyStatus::Claimed);
    }
}
