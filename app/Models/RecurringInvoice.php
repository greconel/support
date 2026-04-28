<?php

namespace App\Models;

use App\Enums\RecurringInvoicePeriod;
use App\Traits\ActivityDescriptionTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RecurringInvoice extends Model
{
    use LogsActivity;
    use ActivityDescriptionTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'period' => RecurringInvoicePeriod::class,
        'last_generated_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::deleting(function (RecurringInvoice $recurringInvoice) {
            $recurringInvoice->billingLines()->delete();
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => $this->descriptionForEvent($event, 'recurring invoice'))
        ;
    }

    public function getAmountFormattedAttribute(): string
    {
        return '€ ' . number_format($this->amount, 2, ',', ' ');
    }

    public function getAmountWithVatFormattedAttribute(): string
    {
        return '€ ' . number_format($this->amount_with_vat, 2, ',', ' ');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function billingLines(): MorphMany
    {
        return $this->morphMany(BillingLines::class, 'morphable', 'model_type', 'model_id');
    }

    public function syncProductPrices(): void
    {
        foreach ($this->billingLines as $line) {
            if ($line->product_id) {
                $product = Product::find($line->product_id);
                if ($product && $line->price != $product->price) {
                    $subtotal = $product->price * $line->amount;
                    $subtotal -= $subtotal * (($line->discount ?? 0) / 100);

                    $line->update([
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ]);
                }
            }
        }
    }
}