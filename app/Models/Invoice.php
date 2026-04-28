<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Traits\ActivityDescriptionTrait;
use App\Traits\InvoiceTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\InvoicePayment;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Invoice extends Model implements HasMedia
{
    use InteractsWithMedia;
    use LogsActivity;
    use ActivityDescriptionTrait;
    use InvoiceTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'expiration_date' => 'date',
        'custom_created_at' => 'datetime',
        'paid_at' => 'datetime',
        'status' => InvoiceStatus::class,
        'type' => InvoiceType::class,
        'sent_to_clearfacts_at' => 'datetime',
        'sent_to_recommand_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function(Invoice $invoice){
            $invoice->number = Invoice::max('number') + 1;

            if ($invoice->type == InvoiceType::Debit){
                $invoice->ogm = $invoice->generateOgm();
            }
        });

        static::deleting(function (Invoice $invoice){
            $invoice->billingLines()->delete();

            foreach ($invoice->emails as $email){
                $email->delete();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => $this->descriptionForEvent($event, 'invoice'))
        ;
    }

    public function scopeDebit(Builder $query)
    {
        return $query->where('type', '=', InvoiceType::Debit);
    }

    public function scopeCredit(Builder $query)
    {
        return $query->where('type', '=', InvoiceType::Credit);
    }

    public function scopeNotUploadedToClearfacts(Builder $query)
    {
        return $query->where('sent_to_clearfacts_at', '=', null);
    }

    public function scopeDisabledForClearfacts(Builder $query, bool $disabled)
    {
        return $query->where('is_disabled_for_clearfacts', '=', $disabled);
    }

    public function getFileNameAttribute()
    {
        return __('invoice') . '_' . $this->custom_created_at->format('Y') . '_' . $this->number . '.pdf';
    }

    public function getFileNameReminderAttribute()
    {
        return __('invoice') . '_' . __('reminder') . '_' . $this->custom_created_at->format('Y') . '_' . $this->number . '.pdf';
    }

    public function getVatAmountFormatted()
    {
        $totalBtw = 0;

        foreach ($this->billingLines as $line){
            if($line->type == 'text'){
                $totalBtw += $line->subtotal * ($line->vat / 100);
            }
        }

        return '€ ' . number_format($totalBtw, 2, ',', ' ');
    }

    public function getCustomNameAttribute()
    {
        return "{$this->custom_created_at->format('Y')}/{$this->number}";
    }

    public function getAmountFormattedAttribute()
    {
        return '€ ' . number_format($this->amount, 2, ',', ' ');
    }

    public function getAmountWithVatFormattedAttribute()
    {
        return '€ ' . number_format($this->amount_with_vat, 2, ',', ' ');
    }

    public function invoiceCategory(): BelongsTo
    {
        return $this->belongsTo(InvoiceCategory::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)->withTrashed();
    }

    public function billingLines(): MorphMany
    {
        return $this->morphMany(BillingLines::class, 'morphable', 'model_type', 'model_id');
    }

    public function emails(): MorphMany
    {
        return $this->morphMany(Email::class, 'morphable', 'model_type', 'model_id');
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function parentInvoice(): BelongsTo
    {
        return $this
            ->belongsTo(Invoice::class, 'parent_invoice_id', 'id')
            ->where('type', '=', InvoiceType::Debit)
        ;
    }

    public function creditNotes(): HasMany
    {
        return $this
            ->hasMany(Invoice::class, 'parent_invoice_id', 'id')
            ->where('type', '=', InvoiceType::Credit)
        ;
    }

    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class);
    }

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function getRemainingBalanceAttribute(): float
    {
        return max(0, $this->amount_with_vat - $this->total_paid);
    }

    public function getRemainingBalanceFormattedAttribute(): string
    {
        return '€ ' . number_format($this->remaining_balance, 2, ',', ' ');
    }

    public function getTotalPaidFormattedAttribute(): string
    {
        return '€ ' . number_format($this->total_paid, 2, ',', ' ');
    }

    public function getIsFullyPaidAttribute(): bool
    {
        return $this->remaining_balance < 0.01;
    }

    public function getIsPartiallyPaidAttribute(): bool
    {
        return $this->total_paid > 0 && !$this->is_fully_paid;
    }
}
