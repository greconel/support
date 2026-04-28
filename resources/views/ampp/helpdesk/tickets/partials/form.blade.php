@php
    $ticket = $ticket ?? null;
    $selectedLabels = $ticket ? $ticket->labels->pluck('id')->all() : [];
@endphp

<div x-data="ticketForm(
    @js($labels->map(fn($l) => ['id' => $l->id, 'name' => $l->name])->values()),
    @js(array_map('intval', old('labels', $selectedLabels)))
)">

    <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;"
         class="hd-form-grid">

        {{-- Left column --}}
        <div class="hd-stack">

            {{-- Client card --}}
            <div class="hd-card">
                <h3 class="hd-h2 hd-mb-4" style="margin-top: 0;">{{ __('Client') }}</h3>

                <div>
                    <label class="hd-label" for="client_id">
                        {{ __('Choose client') }} <span style="color: var(--red-500);">*</span>
                    </label>
                    <select name="client_id" id="client_id" class="hd-select @error('client_id') is-invalid @enderror" required>
                        <option value="">{{ __('Select client…') }}</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('client_id', $ticket?->client_id) == $client->id)>
                                {{ $client->full_name_with_company }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="hd-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Ticket details --}}
            <div class="hd-card">
                <h3 class="hd-h2 hd-mb-4" style="margin-top: 0;">{{ __('Ticket details') }}</h3>

                <div class="hd-mb-4">
                    <label class="hd-label" for="subject">
                        {{ __('Subject') }} <span style="color: var(--red-500);">*</span>
                    </label>
                    <input type="text" name="subject" id="subject"
                        value="{{ old('subject', $ticket?->subject) }}"
                        placeholder="{{ __('Short description of the issue') }}"
                        class="hd-input @error('subject') is-invalid @enderror" required>
                    @error('subject')
                        <p class="hd-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="hd-label" for="description">
                        {{ __('Description') }} <span style="color: var(--red-500);">*</span>
                    </label>
                    <textarea name="description" id="description" rows="6"
                        placeholder="{{ __('Give a detailed description of the issue or question…') }}"
                        class="hd-textarea @error('description') is-invalid @enderror" required>{{ old('description', $ticket?->description) }}</textarea>
                    @error('description')
                        <p class="hd-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div class="hd-stack">

            <div class="hd-card">
                <h3 class="hd-h2 hd-mb-4" style="margin-top: 0;">{{ __('Properties') }}</h3>

                <div class="hd-mb-4">
                    <label class="hd-label" for="impact">{{ __('Impact') }}</label>
                    <select name="impact" id="impact" class="hd-select">
                        <option value="">{{ __('No impact') }}</option>
                        @foreach($impacts as $impact)
                            <option value="{{ $impact->value }}" @selected(old('impact', $ticket?->impact?->value) === $impact->value)>
                                {{ $impact->label() }}
                            </option>
                        @endforeach
                    </select>
                    @error('impact')
                        <p class="hd-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="hd-mb-4">
                    <label class="hd-label" for="assigned_to">{{ __('Assign to') }}</label>
                    <select name="assigned_to" id="assigned_to" class="hd-select">
                        <option value="">{{ __('Unassigned') }}</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" @selected(old('assigned_to', $ticket?->assigned_to) == $agent->id)>
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="hd-xs hd-subtle hd-mt-2" style="margin-bottom: 0;">
                        {{ __('On assignment, the status automatically becomes "In progress".') }}
                    </p>
                    @error('assigned_to')
                        <p class="hd-error">{{ $message }}</p>
                    @enderror
                </div>

                @isset($statuses)
                    <div class="hd-mb-4">
                        <label class="hd-label" for="status">{{ __('Status') }}</label>
                        <select name="status" id="status" class="hd-select" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" @selected(old('status', $ticket?->status?->value) === $status->value)>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="hd-error">{{ $message }}</p>
                        @enderror
                    </div>
                @endisset

                <div>
                    <label class="hd-label" for="labels_picker">{{ __('Labels') }}</label>

                    <div class="hd-flex hd-wrap hd-gap-2 hd-mb-3" x-show="selectedLabelIds.length > 0" x-cloak>
                        <template x-for="labelId in selectedLabelIds" :key="labelId">
                            <div class="hd-badge hd-badge-round hd-badge-gray">
                                <span x-text="labelName(labelId)"></span>
                                <button type="button" class="hd-chip-x" @click="removeSelectedLabel(labelId)" aria-label="{{ __('Remove label') }}">
                                    <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                                <input type="hidden" name="labels[]" :value="labelId">
                            </div>
                        </template>
                    </div>

                    <p class="hd-xs hd-subtle hd-mb-3" x-show="selectedLabelIds.length === 0" x-cloak>
                        {{ __('No labels added yet.') }}
                    </p>

                    <div class="hd-flex hd-gap-2">
                        <select id="labels_picker" x-model="labelToAdd" class="hd-select">
                            <option value="">{{ __('Choose a label…') }}</option>
                            <template x-for="label in availableLabelOptions()" :key="label.id">
                                <option :value="label.id" x-text="label.name"></option>
                            </template>
                        </select>
                        <button type="button" @click="addSelectedLabel()" class="hd-btn hd-btn-secondary" style="white-space: nowrap;">
                            {{ __('Add') }}
                        </button>
                    </div>
                    @error('labels') <p class="hd-error">{{ $message }}</p> @enderror
                    @error('labels.*') <p class="hd-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

</div>

@once
    @push('styles')
        <style>
            @media (min-width: 992px) {
                .hd-form-grid { grid-template-columns: 2fr 1fr !important; }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function ticketForm(availableLabels, selectedLabelIds) {
                return {
                    labelToAdd: '',
                    availableLabels: availableLabels ?? [],
                    selectedLabelIds: (selectedLabelIds ?? []).map(Number),
                    availableLabelOptions() {
                        return this.availableLabels.filter(l => !this.selectedLabelIds.includes(Number(l.id)));
                    },
                    addSelectedLabel() {
                        const id = Number(this.labelToAdd);
                        if (!id || this.selectedLabelIds.includes(id)) return;
                        this.selectedLabelIds.push(id);
                        this.labelToAdd = '';
                    },
                    removeSelectedLabel(id) {
                        const n = Number(id);
                        this.selectedLabelIds = this.selectedLabelIds.filter(x => x !== n);
                    },
                    labelName(id) {
                        const f = this.availableLabels.find(l => Number(l.id) === Number(id));
                        return f ? f.name : '';
                    },
                };
            }
        </script>
    @endpush
@endonce
