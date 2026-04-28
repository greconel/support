<x-layouts.ampp :title="__('Tickets') . ' - Helpdesk'">
    @include('ampp.helpdesk.partials.styles')

    <div class="hd-scope"
         x-data="ticketsIndex()"
         @click.capture="handleClick($event)">
        <div class="hd-page">

            <div class="hd-flex hd-items-center hd-justify-between hd-wrap hd-gap-3 hd-mb-4">
                <h1 class="hd-h1" style="margin: 0;">{{ __('Tickets') }}</h1>

                <div class="hd-flex hd-gap-2">
                    <a href="{{ route('status-board') }}" class="hd-btn hd-btn-ghost">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/>
                        </svg>
                        {{ __('Status Board') }}
                    </a>
                    <a href="{{ route('tickets.create') }}" class="hd-btn hd-btn-primary">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('New ticket') }}
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="hd-alert hd-alert-success">{{ session('success') }}</div>
            @endif

            {{-- Filters --}}
            <div class="hd-card hd-mb-4">
                <form method="GET"
                      action="{{ route('tickets.index') }}"
                      style="display: grid; grid-template-columns: 1fr; gap: 0.5rem;"
                      class="hd-filter-grid"
                      @submit.prevent="search()">
                    <div style="position: relative;">
                        <input type="text" name="q" value="{{ request('q') }}"
                            class="hd-input"
                            placeholder="{{ __('Search subject or number…') }}"
                            @input.debounce.200ms="search()">

                        <div x-show="loading" x-cloak
                             style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); pointer-events: none;">
                            <svg style="width: 1rem; height: 1rem; animation: hd-spin 0.9s linear infinite;" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="10" stroke="var(--gray-200)" stroke-width="3"/>
                                <path d="M12 2a10 10 0 0110 10" stroke="var(--blue-500)" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <select name="status" class="hd-select" @change="search()">
                            <option value="">{{ __('All statuses') }}</option>
                            <option value="open" @selected(request('status') === 'open')>{{ __('Open (all except closed)') }}</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" @selected(request('status') === $status->value)>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="button" class="hd-btn hd-btn-secondary hd-btn-block" @click="reset()">{{ __('Reset') }}</button>
                    </div>
                </form>
            </div>

            {{-- Resultaten (ajax-swappable) --}}
            <div id="tickets-results">
                @include('ampp.helpdesk.tickets.partials.results', compact('tickets'))
            </div>

        </div>
    </div>

    @once
        @push('styles')
            <style>
                @media (min-width: 768px) {
                    .hd-filter-grid { grid-template-columns: 2fr 1fr auto !important; }
                    .hd-filter-grid .hd-btn-block { min-width: 6rem; }
                }
                @keyframes hd-spin { to { transform: translateY(-50%) rotate(360deg); } }
            </style>
        @endpush

        @push('scripts')
            <script>
                function ticketsIndex() {
                    return {
                        loading: false,

                        buildUrl(extraParams = {}) {
                            const form = this.$root.querySelector('form');
                            const data = new URLSearchParams(new FormData(form));

                            // Strip lege waarden zodat de URL schoon blijft
                            for (const [k, v] of [...data.entries()]) {
                                if (v === '' || v === null) data.delete(k);
                            }
                            for (const [k, v] of Object.entries(extraParams)) {
                                if (v !== null && v !== undefined && v !== '') {
                                    data.set(k, v);
                                }
                            }

                            const qs = data.toString();
                            return form.action + (qs ? '?' + qs : '');
                        },

                        async fetchInto(url) {
                            this.loading = true;
                            try {
                                const res = await fetch(url, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'text/html',
                                    },
                                    credentials: 'same-origin',
                                });
                                if (!res.ok) throw new Error('HTTP ' + res.status);
                                const html = await res.text();
                                const target = document.getElementById('tickets-results');
                                if (target) target.innerHTML = html;
                                history.replaceState(null, '', url);
                            }
                            catch (e) {
                                console.error('Search failed', e);
                            }
                            finally {
                                this.loading = false;
                            }
                        },

                        search() {
                            this.fetchInto(this.buildUrl());
                        },

                        reset() {
                            const form = this.$root.querySelector('form');
                            form.reset();
                            // Forceer selects zichtbaar terug op default want .reset() doet dat soms niet consistent
                            form.querySelectorAll('select').forEach(s => { s.selectedIndex = 0; });
                            this.fetchInto(form.action);
                        },

                        // Intercept pagination-klikken (ajax) en rij-klikken (navigate)
                        handleClick(e) {
                            // Als er tekst geselecteerd is, doe niks — user selecteert gewoon iets
                            if (window.getSelection && window.getSelection().toString().length > 0) {
                                return;
                            }

                            // Middle click / ctrl+click / meta+click: laat browser standaard gedrag (nieuwe tab) doen
                            if (e.button === 1 || e.ctrlKey || e.metaKey || e.shiftKey) {
                                return;
                            }

                            // 1) Pagination-links binnen resultaten → ajax-swap
                            const paginationLink = e.target.closest('#tickets-results .pagination a, #tickets-results [role="navigation"] a');
                            if (paginationLink) {
                                const href = paginationLink.getAttribute('href');
                                if (href && !href.startsWith('#') && paginationLink.target !== '_blank') {
                                    e.preventDefault();
                                    this.fetchInto(href);
                                    return;
                                }
                            }

                            // 2) Rij-klik → navigeer naar ticket (tenzij op een echte link/button in de rij)
                            const row = e.target.closest('tr[data-href]');
                            if (row && !e.target.closest('a, button')) {
                                const href = row.dataset.href;
                                if (href) window.location.assign(href);
                            }
                        },
                    };
                }
            </script>
        @endpush
    @endonce
</x-layouts.ampp>
