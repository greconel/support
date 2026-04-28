<?php

namespace App\Models;

use App\Enums\InvoiceType;
use App\Enums\QuotationStatus;
use App\Traits\ActivityDescriptionTrait;
use App\Traits\QuotationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Quotation extends Model implements HasMedia
{
    use InteractsWithMedia;
    use LogsActivity;
    use ActivityDescriptionTrait;
    use QuotationTrait;

    protected $guarded = ['id'];

    protected $casts = [
        'expiration_date' => 'date',
        'custom_created_at' => 'datetime',
        'accepted_at' => 'datetime',
        'status' => QuotationStatus::class
    ];

    protected static function booted()
    {
        static::deleting(function (Quotation $quotation){
            $quotation->billingLines()->delete();

            foreach ($quotation->emails as $email){
                $email->delete();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => $this->descriptionForEvent($event, 'quotation'))
        ;
    }

    public function getFileNameAttribute()
    {
        return __('quotation') . '_' . $this->custom_created_at->format('Y') . '_' . $this->number . '.pdf';
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

    public function invoices(): HasMany
    {
        return $this
            ->hasMany(Invoice::class)
            ->where('type', '=', InvoiceType::Debit)
        ;
    }

    public function creditNotes(): HasMany
    {
        return $this
            ->hasMany(Invoice::class)
            ->where('type', '=', InvoiceType::Credit)
        ;
    }

    public function deal(): HasOne
    {
        return $this->hasOne(Deal::class);
    }
}
