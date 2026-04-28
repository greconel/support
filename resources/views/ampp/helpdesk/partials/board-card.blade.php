@php
    $statusClass = [
        'new' => 'hd-badge-soft-slate',
        'in_progress' => 'hd-badge-soft-blue',
        'on_hold' => 'hd-badge-soft-amber',
        'to_close' => 'hd-badge-soft-violet',
        'closed' => 'hd-badge-soft-emerald',
    ][$ticket->status->value] ?? 'hd-badge-soft-gray';

    $impactClass = [
        'low' => 'hd-badge-soft-green',
        'medium' => 'hd-badge-soft-amber',
        'high' => 'hd-badge-soft-red',
    ][$ticket->impact?->value ?? ''] ?? null;
@endphp

<div class="hd-ticket-card"
     data-id="{{ $ticket->id }}"
     data-status="{{ $ticket->status->value }}"
     x-show="isActive('{{ $ticket->status->value }}')">
    <a href="{{ route('tickets.show', ['ticket' => $ticket, 'from' => 'agents']) }}">
        <div class="hd-flex hd-items-start hd-justify-between hd-mb-2">
            <span class="hd-ticket-num">{{ $ticket->ticket_number }}</span>
            @if($impactClass)
                <span class="hd-badge {{ $impactClass }}">{{ ucfirst($ticket->impact->value) }}</span>
            @endif
        </div>
        <h3 class="hd-ticket-title">{{ $ticket->subject }}</h3>
        <div style="margin-bottom: 0.625rem;">
            <p class="hd-xs hd-dim" style="margin: 0;">
                <span class="hd-fw-medium">{{ __('Client') }}:</span>
                {{ $ticket->client?->full_name_with_company ?? '—' }}
            </p>
        </div>
        <div class="hd-flex hd-items-center hd-gap-2 hd-wrap">
            <span class="hd-badge {{ $statusClass }}">{{ $ticket->status->label() }}</span>
            @foreach($ticket->labels->take(2) as $label)
                <span class="hd-badge hd-badge-soft-gray">{{ $label->name }}</span>
            @endforeach
            @if($ticket->labels->count() > 2)
                <span class="hd-xs hd-muted">+{{ $ticket->labels->count() - 2 }}</span>
            @endif
        </div>
    </a>
</div>
