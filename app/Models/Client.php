<?php

namespace App\Models;

use App\Enums\ClientType;
use App\Traits\ActivityDescriptionTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Client extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;
    use ActivityDescriptionTrait;
    use InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => ClientType::class,
        'peppol_only' => 'boolean',
    ];

    protected static function booted()
    {
        static::deleting(function (Client $client) {
            foreach ($client->todos as $todo) {
                $todo->delete();
            }

            foreach ($client->notes as $note) {
                $note->delete();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn (string $event) => $this->descriptionForEvent($event, 'client'))
        ;
    }

    public function scopeClients(Builder $query): Builder
    {
        return $query->where('type', '=', ClientType::Client);
    }

    public function scopeLeads(Builder $query): Builder
    {
        return $query->where('type', '=', ClientType::Lead);
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function getFullNameWithEmailAttribute()
    {
        return trim("$this->first_name $this->last_name <$this->email>");
    }

    public function getFullNameBackwardsAttribute()
    {
        return trim("{$this->last_name} {$this->first_name}");
    }

    public function getFullNameWithCompanyAttribute()
    {
        $value = trim("{$this->first_name} {$this->last_name}");

        if ($this->company){
            $value .= " - {$this->company}";
        }

        return trim($value);
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

    public function contacts(): HasMany
    {
        return $this->hasMany(ClientContact::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'morphable', 'model_type', 'model_id');
    }

    public function todos(): MorphMany
    {
        return $this->morphMany(Todo::class, 'morphable', 'model_type', 'model_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function timeRegistrations(): HasManyThrough
    {
        return $this->hasManyThrough(TimeRegistration::class, Project::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function getValidatedVatAttribute(): ?string
    {
        if (empty($this->vat)) {
            return null;
        }

        $cleanVat = strtoupper(preg_replace('/[\s\.\-]/', '', $this->vat));

        $vatPatterns = [
            'BE' => '/^BE\d{10}$/',
            'NL' => '/^NL\d{9}B\d{2}$/',
            'DE' => '/^DE\d{9}$/',
            'FR' => '/^FR[A-Z0-9]{2}\d{9}$/',
            'GB' => '/^GB\d{9}$|^GB\d{12}$|^GBGD\d{3}$|^GBHA\d{3}$/',
        ];

        foreach ($vatPatterns as $country => $pattern) {
            if (preg_match($pattern, $cleanVat)) {
                return $cleanVat;
            }
        }

        return null;
    }
}
