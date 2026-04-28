<?php

namespace App\Models;

use App\Enums\AiCorrectionType;
use App\Enums\TicketImpact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiCorrectionLog extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'ai_impact' => TicketImpact::class,
        'agent_impact' => TicketImpact::class,
        'ai_labels' => 'array',
        'agent_labels' => 'array',
        'correction_type' => AiCorrectionType::class,
        'processed' => 'boolean',
        'ignore_in_training' => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function impactCorrect(): bool
    {
        return $this->ai_impact === $this->agent_impact;
    }

    public function labelsCorrect(): bool
    {
        $ai = collect($this->ai_labels ?? [])->sort()->values()->toArray();
        $agent = collect($this->agent_labels ?? [])->sort()->values()->toArray();

        return $ai === $agent;
    }

    public function fullyCorrect(): bool
    {
        return $this->impactCorrect() && $this->labelsCorrect();
    }
}
