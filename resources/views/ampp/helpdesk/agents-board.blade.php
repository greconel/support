<x-layouts.ampp :title="__('Agents Board') . ' - Helpdesk'">
    @include('ampp.helpdesk.partials.styles')

    <div class="hd-scope"
         x-data="{
            active: ['new', 'in_progress'],
            toggle(status) {
                const idx = this.active.indexOf(status);
                if (idx !== -1) {
                    if (this.active.length > 1) this.active.splice(idx, 1);
                } else {
                    this.active.push(status);
                }
            },
            isActive(status) { return this.active.includes(status); }
         }">

        {{-- Filter toolbar --}}
        <div class="hd-flex hd-items-center hd-gap-3 hd-wrap" style="padding: 0.75rem 1.5rem; background: #fff; border-bottom: 1px solid var(--gray-200);">
            <span class="hd-xs hd-fw-medium hd-muted">{{ __('Show statuses') }}:</span>

            <button type="button" @click="toggle('new')"
                    :class="isActive('new') ? 'active-slate' : ''"
                    class="hd-filter-btn">
                <span class="hd-status-dot slate"></span>
                {{ __('New') }}
            </button>
            <button type="button" @click="toggle('in_progress')"
                    :class="isActive('in_progress') ? 'active-blue' : ''"
                    class="hd-filter-btn">
                <span class="hd-status-dot blue"></span>
                {{ __('In progress') }}
            </button>
            <button type="button" @click="toggle('on_hold')"
                    :class="isActive('on_hold') ? 'active-amber' : ''"
                    class="hd-filter-btn">
                <span class="hd-status-dot amber"></span>
                {{ __('On hold') }}
            </button>
            <button type="button" @click="toggle('to_close')"
                    :class="isActive('to_close') ? 'active-violet' : ''"
                    class="hd-filter-btn">
                <span class="hd-status-dot violet"></span>
                {{ __('To close') }}
            </button>
        </div>

        <div class="hd-page">
            <div class="hd-board-wrap">

                {{-- Unassigned --}}
                <div class="hd-col">
                    <div class="hd-col-header gray">
                        <div class="hd-flex hd-items-center hd-justify-between" style="width: 100%;">
                            <h2 class="hd-h3" style="margin: 0;">{{ __('Unassigned') }}</h2>
                            <span class="hd-badge hd-badge-gray ticket-count">{{ $unassignedTickets->count() }}</span>
                        </div>
                    </div>

                    <div class="hd-col-body agent-column" data-agent-id="">
                        @forelse($unassignedTickets as $ticket)
                            @include('ampp.helpdesk.partials.board-card', ['ticket' => $ticket])
                        @empty
                            <div class="empty-placeholder hd-empty">
                                <p style="margin: 0;">{{ __('No tickets') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Agent columns --}}
                @foreach($agents as $agent)
                    @php
                        $agentTickets = $agent->assignedTickets()
                            ->with(['client', 'labels'])
                            ->whereIn('status', $openStatuses)
                            ->orderByDesc('updated_at')
                            ->get();
                    @endphp
                    <div class="hd-col">
                        <div class="hd-col-header blue">
                            <div class="hd-flex hd-items-center hd-justify-between" style="width: 100%;">
                                <h2 class="hd-h3" style="margin: 0;">{{ $agent->name }}</h2>
                                <span class="hd-badge hd-badge-blue ticket-count">{{ $agentTickets->count() }}</span>
                            </div>
                        </div>

                        <div class="hd-col-body agent-column" data-agent-id="{{ $agent->id }}">
                            @forelse($agentTickets as $ticket)
                                @include('ampp.helpdesk.partials.board-card', ['ticket' => $ticket])
                            @empty
                                <div class="empty-placeholder hd-empty">
                                    <p style="margin: 0;">{{ __('No tickets') }}</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>

            @if($agents->isEmpty())
                <div class="hd-alert hd-alert-info hd-mt-3">
                    {{ __('No users with the "helpdesk agent" role yet. Assign the role to users to see them here.') }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
        <script>
            (function(){
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
                               || '{{ csrf_token() }}';
                const moveUrl = (id) => `/ampp/tickets/${id}/move`;

                document.querySelectorAll('.agent-column').forEach(column => {
                    Sortable.create(column, {
                        group: 'agents-board',
                        animation: 150,
                        ghostClass: 'hd-ghost',
                        dragClass: 'hd-drag',
                        handle: '.hd-ticket-card',
                        onEnd(event) {
                            const ticketId = event.item.dataset.id;
                            const newAgentId = event.to.dataset.agentId;
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
                                body: JSON.stringify({
                                    assigned_to: newAgentId === '' ? null : newAgentId,
                                }),
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
