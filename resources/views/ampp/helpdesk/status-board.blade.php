<x-layouts.ampp :title="__('Status Board') . ' - Helpdesk'">
    @include('ampp.helpdesk.partials.styles')

    <div class="hd-scope">
        <div class="hd-page">
            <h1 class="hd-h1 hd-mb-4">{{ __('Status Board') }}</h1>

            <div class="hd-board-wrap">
                @foreach($statuses as $statusKey => $statusLabel)
                    @php
                        $tickets = $ticketsByStatus[$statusKey];
                        $colMap = [
                            'new'         => ['header' => 'slate',   'badge' => 'hd-badge-slate'],
                            'in_progress' => ['header' => 'blue',    'badge' => 'hd-badge-blue'],
                            'on_hold'     => ['header' => 'amber',   'badge' => 'hd-badge-amber'],
                            'to_close'    => ['header' => 'violet',  'badge' => 'hd-badge-violet'],
                            'closed'      => ['header' => 'emerald', 'badge' => 'hd-badge-emerald'],
                        ];
                        $style = $colMap[$statusKey] ?? ['header' => 'gray', 'badge' => 'hd-badge-gray'];
                    @endphp

                    <div class="hd-col">
                        <div class="hd-col-header {{ $style['header'] }}">
                            <div class="hd-flex hd-items-center hd-justify-between" style="width: 100%;">
                                <h2 class="hd-h3" style="margin: 0;">{{ $statusLabel }}</h2>
                                <span class="hd-badge {{ $style['badge'] }} ticket-count">{{ $tickets->count() }}</span>
                            </div>
                        </div>

                        <div
                            class="hd-col-body ticket-column"
                            data-status="{{ $statusKey }}"
                        >
                            @forelse($tickets as $ticket)
                                <div class="hd-ticket-card" data-id="{{ $ticket->id }}">
                                    <a href="{{ route('tickets.show', ['ticket' => $ticket, 'from' => 'status']) }}">
                                        <div class="hd-flex hd-items-start hd-justify-between hd-mb-2">
                                            <span class="hd-ticket-num">{{ $ticket->ticket_number }}</span>
                                            @if($ticket->impact)
                                                @php
                                                    $impactClass = [
                                                        'low' => 'hd-badge-soft-green',
                                                        'medium' => 'hd-badge-soft-amber',
                                                        'high' => 'hd-badge-soft-red',
                                                    ][$ticket->impact->value] ?? 'hd-badge-soft-gray';
                                                @endphp
                                                <span class="hd-badge {{ $impactClass }}">{{ ucfirst($ticket->impact->value) }}</span>
                                            @endif
                                        </div>
                                        <h3 class="hd-ticket-title">{{ $ticket->subject }}</h3>
                                        <div style="margin-bottom: 0.625rem;">
                                            <p class="hd-xs hd-dim" style="margin: 0;">
                                                <span class="hd-fw-medium">{{ __('Client') }}:</span>
                                                {{ $ticket->client?->full_name_with_company ?? '—' }}
                                            </p>
                                            @if($ticket->agent)
                                                <p class="hd-xs hd-dim" style="margin: 0;">
                                                    <span class="hd-fw-medium">{{ __('Agent') }}:</span>
                                                    {{ $ticket->agent->name }}
                                                </p>
                                            @else
                                                <p class="hd-xs hd-subtle" style="margin: 0;">
                                                    <span class="hd-fw-medium">{{ __('Agent') }}:</span>
                                                    {{ __('Unassigned') }}
                                                </p>
                                            @endif
                                        </div>
                                        @if($ticket->labels->count() > 0)
                                            <div class="hd-flex hd-wrap hd-gap-1">
                                                @foreach($ticket->labels->take(3) as $label)
                                                    <span class="hd-badge hd-badge-soft-gray">{{ $label->name }}</span>
                                                @endforeach
                                                @if($ticket->labels->count() > 3)
                                                    <span class="hd-xs hd-muted">+{{ $ticket->labels->count() - 3 }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </a>
                                </div>
                            @empty
                                <div class="empty-placeholder hd-empty">
                                    <p style="margin: 0;">{{ __('No tickets') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
        <script>
            (function(){
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
                               || '{{ csrf_token() }}';
                const moveUrl = (id) => `/ampp/tickets/${id}/move`;

                document.querySelectorAll('.ticket-column').forEach(column => {
                    Sortable.create(column, {
                        group: 'status-board',
                        animation: 150,
                        ghostClass: 'hd-ghost',
                        dragClass: 'hd-drag',
                        handle: '.hd-ticket-card',
                        onEnd(event) {
                            const ticketId = event.item.dataset.id;
                            const newStatus = event.to.dataset.status;
                            updatePlaceholders(event.from);
                            updatePlaceholders(event.to);
                            updateCount(event.from);
                            updateCount(event.to);

                            fetch(moveUrl(ticketId), {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                                body: JSON.stringify({ status: newStatus }),
                            })
                            .then(res => res.ok ? res.json() : Promise.reject(res.status))
                            .catch(() => {
                                alert('{{ __("Could not update ticket. Reloading.") }}');
                                location.reload();
                            });
                        },
                    });
                });

                function updatePlaceholders(column) {
                    const cards = column.querySelectorAll('.hd-ticket-card');
                    let placeholder = column.querySelector('.empty-placeholder');
                    if (cards.length === 0) {
                        if (!placeholder) {
                            placeholder = document.createElement('div');
                            placeholder.className = 'empty-placeholder hd-empty';
                            placeholder.innerHTML = '<p style="margin:0;">{{ __("No tickets") }}</p>';
                            column.appendChild(placeholder);
                        }
                    } else if (placeholder) {
                        placeholder.remove();
                    }
                }

                function updateCount(column) {
                    const col = column.closest('.hd-col');
                    const count = column.querySelectorAll('.hd-ticket-card').length;
                    const badge = col?.querySelector('.ticket-count');
                    if (badge) badge.textContent = count;
                }
            })();
        </script>
    @endpush
</x-layouts.ampp>
