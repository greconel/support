<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BillingLines extends Model
{
    protected $guarded = ['id'];

    public static function getVatValues(): array
    {
        $values = config('ampp.billing.vat_values');

        foreach ($values as &$value){
            $value = __(':value% VAT', ['value' => $value]);
        }

        return $values;
    }

    public function getPriceFormattedAttribute()
    {
        return '€ ' . number_format($this->price, 2, ',', ' ');
    }

    public function getSubTotalFormattedAttribute()
    {
        return '€ ' . number_format($this->subtotal, 2, ',', ' ');
    }

    public function getAmountFormattedAttribute()
    {
        return floatval($this->amount);
    }

    public function getVatFormattedAttribute()
    {
        return floatval($this->vat). '%';
    }

    public function morphable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }
}
