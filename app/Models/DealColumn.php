<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DealColumn extends Model
{
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::deleting(function (DealColumn $dealColumn){
            foreach ($dealColumn->deals as $deal) {
                $deal->delete();
            }
        });
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }
}
