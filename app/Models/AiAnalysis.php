<?php

namespace App\Models;

use App\Enums\TicketImpact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAnalysis extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'impact' => TicketImpact::class,
        'labels' => 'array',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
