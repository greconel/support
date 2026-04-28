<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Deal extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'due_date' => 'datetime',
        'expected_start_date' => 'date'
    ];

    protected static function booted()
    {
        static::deleting(function (Deal $deal) {
            foreach ($deal->todos as $todo) {
                $todo->delete();
            }

            foreach ($deal->notes as $note) {
                $note->delete();
            }
        });
    }

    public function getExpectedRevenueFormattedAttribute()
    {
        return number_format($this->expected_revenue, 2, ',', ' ');
    }

    public function dealColumn(): BelongsTo
    {
        return $this->belongsTo(DealColumn::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function todos(): MorphMany
    {
        return $this->morphMany(Todo::class, 'morphable', 'model_type', 'model_id');
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'morphable', 'model_type', 'model_id');
    }
}
