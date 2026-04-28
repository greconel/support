<div class="hd-table-wrap">
    <table class="hd-table">
        <thead>
            <tr>
                <th>{{ __('Ticket') }}</th>
                <th>{{ __('Subject') }}</th>
                <th>{{ __('Client') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Impact') }}</th>
                <th>{{ __('Labels') }}</th>
                <th>{{ __('Agent') }}</th>
                <th style="text-align: right;">{{ __('Updated') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                @php
                    $statusClass = [
                        'new' => 'hd-badge-slate',
                        'in_progress' => 'hd-badge-blue',
                        'on_hold' => 'hd-badge-amber',
                        'to_close' => 'hd-badge-violet',
                        'closed' => 'hd-badge-emerald',
                    ][$ticket->status->value] ?? 'hd-badge-gray';

                    $impactClass = [
                        'low' => 'hd-badge-green',
                        'medium' => 'hd-badge-amber',
                        'high' => 'hd-badge-red',
                    ][$ticket->impact?->value ?? ''] ?? null;
                @endphp
                <tr class="hd-row-link" data-href="{{ route('tickets.show', $ticket) }}">
                    <td>
                        <span class="hd-text-link hd-fw-semibold">
                            {{ $ticket->ticket_number }}
                        </span>
                    </td>
                    <td style="max-width: 380px;">
                        <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $ticket->subject }}
                        </div>
                    </td>
                    <td>{{ $ticket->client?->full_name_with_company ?? '—' }}</td>
                    <td>
                        <span class="hd-badge {{ $statusClass }}">{{ $ticket->status->label() }}</span>
                    </td>
                    <td>
                        @if($impactClass)
                            <span class="hd-badge {{ $impactClass }}">{{ ucfirst($ticket->impact->value) }}</span>
                        @else
                            <span class="hd-subtle">—</span>
                        @endif
                    </td>
                    <td>
                        @if($ticket->labels->count() > 0)
                            <div style="display: flex; flex-wrap: wrap; gap: 0.35rem; max-width: 240px;">
                                @foreach($ticket->labels->take(3) as $label)
                                    <span class="hd-badge hd-badge-gray">{{ $label->name }}</span>
                                @endforeach
                                @if($ticket->labels->count() > 3)
                                    <span class="hd-xs hd-subtle">+{{ $ticket->labels->count() - 3 }}</span>
                                @endif
                            </div>
                        @else
                            <span class="hd-subtle">—</span>
                        @endif
                    </td>
                    <td>{{ $ticket->agent?->name ?? __('Unassigned') }}</td>
                    <td style="text-align: right;" class="hd-xs hd-subtle">{{ $ticket->updated_at->diffForHumans() }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="hd-empty">{{ __('No tickets found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($tickets->hasPages())
        <div style="padding: 1rem; border-top: 1px solid var(--gray-100);">
            {{ $tickets->links() }}
        </div>
    @endif
</div>
