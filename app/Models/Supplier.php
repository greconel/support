<?php

namespace App\Models;

use App\Traits\ActivityDescriptionTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Supplier extends Model
{
    use LogsActivity;
    use ActivityDescriptionTrait;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => $this->descriptionForEvent($event, 'supplier'))
        ;
    }

    public function getFullNameAttribute()
    {
        return trim("$this->first_name $this->last_name");
    }

    public function getCompanyWithFullNameAttribute()
    {
        return trim("$this->company | $this->first_name $this->last_name", " \t\n\r\0\x0B|");
    }

    public function getAddressAttribute()
    {
        return trim("$this->street $this->number, <br /> $this->postal $this->city, <br /> $this->country", " \t\n\r\0\x0B,");
    }

    public function getGoogleMapsUrlAttribute()
    {
        $apiKey = config('services.google.maps');

        $address = trim("$this->street+$this->number+$this->postal+$this->city+$this->country", " \t\n\r\0\x0B+");

        return "https://www.google.com/maps/embed/v1/place?q=$address&key=$apiKey";
    }

    public function invoiceCategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InvoiceCategory::class);
    }

    public function scopeNotGeneral(Builder $query)
    {
        return $query->where('is_general', '=', false);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}
