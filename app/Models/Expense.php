<?php

namespace App\Models;

use App\Enums\ExpenseStatus;
use App\Enums\VariousTransactionCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Expense extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'invoice_date' => 'date',
        'status' => ExpenseStatus::class,
        'various_transaction_category' => VariousTransactionCategory::class,
        'paid_at' => 'datetime',
        'sent_to_clearfacts_at' => 'datetime'
    ];

    public static function booted()
    {
        static::creating(fn(Expense $expense) => $expense->number = Expense::max('number') + 1);
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('default')
            ->singleFile()
        ;
    }

    public function scopeNotUploadedToClearfacts(Builder $query)
    {
        return $query->where('sent_to_clearfacts_at', '=', null);
    }

    public function scopeDisabledForClearfacts(Builder $query, bool $disabled)
    {
        return $query->whereRelation('supplier', 'is_disabled_for_clearfacts', '=', $disabled);
    }

    public function getAmountExcludingVatFormattedAttribute()
    {
        return '€ ' . number_format($this->amount_excluding_vat, 2, ',', ' ');
    }

    public function getAmountIncludingVatFormattedAttribute()
    {
        return '€ ' . number_format($this->amount_including_vat, 2, ',', ' ');
    }

    public function getAmountVatFormattedAttribute()
    {
        return '€ ' . number_format($this->amount_vat, 2, ',', ' ');
    }

    public function getAmountVatPercentageAttribute()
    {
        return number_format(((abs($this->amount_including_vat) - abs($this->amount_excluding_vat)) / max(abs($this->amount_excluding_vat), 1)) * 100, 2);
    }

    public function invoiceCategory(): BelongsTo
    {
        return $this->belongsTo(InvoiceCategory::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
