<x-layouts.ampp :title="__('AI Skill Management') . ' - Helpdesk'">
    @include('ampp.helpdesk.partials.styles')

    <div class="hd-scope"
         x-data="{
            active: ['unprocessed'],
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
        <div class="hd-page">

            <h1 class="hd-h1 hd-mb-4">{{ __('AI Skill Management') }}</h1>

            {{-- Flash --}}
            @foreach(['success' => 'hd-alert-success', 'info' => 'hd-alert-info', 'error' => 'hd-alert-error'] as $type => $class)
                @if(session($type))
                    <div class="hd-alert {{ $class }}">{{ session($type) }}</div>
                @endif
            @endforeach

            {{-- Status banner --}}
            <div class="hd-card hd-mb-4" style="padding: 1rem;">
                <div class="hd-flex hd-items-center hd-justify-between hd-wrap hd-gap-4">
                    <div class="hd-flex hd-items-center hd-gap-3">
                        <div class="hd-icon-bubble {{ $pendingCount >= $threshold ? 'amber' : 'gray' }}" style="width: 2.5rem; height: 2.5rem;">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.347.347a5 5 0 01-1.651.928l-1.39.39A2 2 0 019.56 18h-.12a2 2 0 01-1.907-1.383l-.39-1.39a5 5 0 01.928-1.651l.347-.347z"/>
                            </svg>
                        </div>

                        <div>
                            <p class="hd-fw-semibold hd-sm" style="margin: 0; color: var(--gray-900);">
                                {{ $pendingCount }}
                                {{ $pendingCount === 1 ? __('unprocessed correction') : __('unprocessed corrections') }}
                            </p>
                            <p class="hd-xs hd-subtle" style="margin: 0.25rem 0 0 0;">
                                {{ __('AI skill wordt automatisch geüpdatet na :threshold correcties.', ['threshold' => $threshold]) }}
                            </p>
                            <p class="hd-xs hd-muted" style="margin: 0;">
                                @if($pendingCount === 0)
                                    {{ __('Skill is up-to-date.') }}
                                @elseif($pendingCount < $threshold)
                                    {{ __('Automatic update at :threshold corrections (still :remaining to go).', ['threshold' => $threshold, 'remaining' => $threshold - $pendingCount]) }}
                                @else
                                    {{ __('Threshold reached — update recommended or starts automatically.') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div style="flex: 1 1 0; min-width: 120px; max-width: 200px;">
                        @php $progress = min(100, ($pendingCount / max($threshold, 1)) * 100); @endphp
                        <div class="hd-flex hd-items-center hd-justify-between hd-xs hd-subtle" style="margin-bottom: 0.25rem;">
                            <span>{{ __('Progress') }}</span>
                            <span>{{ $pendingCount }}/{{ $threshold }}</span>
                        </div>
                        <div class="hd-progress-track">
                            <div class="hd-progress-bar {{ $pendingCount >= $threshold ? 'warn' : '' }}" style="width: {{ $progress }}%;"></div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('ai-skill.trigger-update') }}">
                        @csrf
                        <button type="submit" class="hd-btn hd-btn-purple" {{ $pendingCount === 0 ? 'disabled' : '' }}>
                            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ __('Update now') }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Two column: editor + corrections --}}
            <div class="hd-cols-2">

                {{-- Skill editor --}}
                <div class="hd-card">
                    <div class="hd-flex hd-items-center hd-justify-between hd-mb-4">
                        <div>
                            <h2 class="hd-h2" style="margin: 0;">{{ __('Skill file') }}</h2>
                            <p class="hd-xs hd-muted" style="margin: 0.25rem 0 0 0;">
                                {{ __('This file controls how the AI labels tickets. Changes take effect immediately.') }}
                            </p>
                        </div>
                        <div style="text-align: right;">
                            <span class="hd-xs hd-subtle hd-mono" style="display: block;">labeling-skill.md</span>
                            <span class="hd-xs hd-muted" style="display: block; margin-top: 0.25rem;">
                                {{ __('Last updated') }}:
                                {{ $skillLastUpdatedAt ? $skillLastUpdatedAt->format('d-m-Y H:i') : __('unknown') }}
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('ai-skill.update') }}">
                        @csrf
                        @method('PATCH')

                        <textarea name="skill_content" rows="32"
                            class="hd-textarea hd-mono"
                            style="font-size: 0.75rem;">{{ $skillContent }}</textarea>

                        @error('skill_content')
                            <p class="hd-error">{{ $message }}</p>
                        @enderror

                        <div class="hd-flex hd-items-center hd-justify-between hd-mt-4">
                            <p class="hd-xs hd-subtle" style="margin: 0;">
                                {{ __('A backup is created automatically on save.') }}
                            </p>
                            <button type="submit" class="hd-btn hd-btn-purple">
                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                </svg>
                                {{ __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Corrections panel --}}
                <div class="hd-card">
                    <div class="hd-flex hd-items-center hd-justify-between hd-mb-4">
                        <div>
                            <h2 class="hd-h2" style="margin: 0;">{{ __('Corrections') }}</h2>
                            <p class="hd-xs hd-muted" style="margin: 0.25rem 0 0 0;">
                                {{ __('Processed corrections are hidden by default. Mark corrections as exception so the AI does not learn from them.') }}
                            </p>
                        </div>
                        <div class="hd-flex hd-items-center hd-gap-2">
                            <a href="{{ route('corrections.export') }}" class="hd-btn hd-btn-ghost hd-btn-sm">
                                <svg style="width: 0.875rem; height: 0.875rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                {{ __('Export CSV') }}
                            </a>
                            <span class="hd-xs hd-muted">{{ $corrections->total() }} {{ __('total') }}</span>
                        </div>
                    </div>

                    <div class="hd-flex hd-items-center hd-gap-3 hd-wrap hd-mb-4">
                        <span class="hd-xs hd-fw-medium hd-muted">{{ __('Show statuses') }}:</span>

                        <button type="button" @click="toggle('unprocessed')"
                                :class="isActive('unprocessed') ? 'active-slate' : ''"
                                class="hd-filter-btn">
                            <span class="hd-status-dot slate"></span>
                            {{ __('Unprocessed') }}
                        </button>

                        <button type="button" @click="toggle('processed')"
                                :class="isActive('processed') ? 'active-blue' : ''"
                                class="hd-filter-btn">
                            <span class="hd-status-dot blue"></span>
                            {{ __('Processed') }}
                        </button>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @forelse($corrections as $log)
                            <div
                                x-data="{ ignore: {{ $log->ignore_in_training ? 'true' : 'false' }}, open: false }"
                                data-status="{{ $log->processed ? 'processed' : 'unprocessed' }}"
                                x-show="isActive('{{ $log->processed ? 'processed' : 'unprocessed' }}')"
                                :style="ignore ? 'border-color: var(--amber-200); background: var(--amber-50);' : ''"
                                style="border: 1px solid var(--gray-200); background: #fff; border-radius: 0.5rem;"
                            >
                                <div class="hd-flex hd-items-center hd-gap-3" style="padding: 0.75rem 1rem;">
                                    <div style="flex: 1 1 0; min-width: 0;">
                                        <div class="hd-flex hd-items-center hd-gap-2 hd-wrap">
                                            <a href="{{ route('tickets.show', $log->ticket_id) }}" class="hd-text-link hd-xs hd-fw-semibold">
                                                {{ $log->ticket?->ticket_number ?? '#?' }}
                                            </a>
                                            <span class="hd-xs hd-dim" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                {{ \Illuminate\Support\Str::limit($log->ticket_subject, 50) }}
                                            </span>
                                        </div>
                                        <div class="hd-flex hd-items-center hd-gap-2 hd-wrap hd-mt-1">
                                            @php
                                                $typeBadge = [
                                                    'impact_only' => ['hd-badge-soft-blue', __('Impact only')],
                                                    'labels_only' => ['hd-badge-soft-violet', __('Labels only')],
                                                    'both' => ['hd-badge-soft-red', __('Impact + labels')],
                                                ][$log->correction_type?->value] ?? ['hd-badge-soft-gray', $log->correction_type?->value];
                                            @endphp
                                            <span class="hd-badge {{ $typeBadge[0] }}" style="font-size: 10px;">{{ $typeBadge[1] }}</span>

                                            @if($log->processed)
                                                <span class="hd-badge hd-badge-soft-emerald" style="font-size: 10px;">{{ __('Processed') }}</span>
                                            @else
                                                <span class="hd-badge hd-badge-soft-gray" style="font-size: 10px;">{{ __('Unprocessed') }}</span>
                                            @endif

                                            <span class="hd-xs hd-subtle">
                                                {{ $log->created_at->format('d-m-Y H:i') }}
                                                @if($log->agent) · {{ $log->agent->name }} @endif
                                            </span>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('corrections.ignore', $log) }}" x-ref="form{{ $log->id }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="ignore_in_training" :value="ignore ? '1' : '0'">
                                        <input type="hidden" name="ignore_reason" value="{{ $log->ignore_reason }}">
                                    </form>

                                    <button type="button"
                                        @click="ignore = !ignore; $nextTick(() => $refs.form{{ $log->id }}.submit())"
                                        :class="ignore ? 'on' : ''"
                                        class="hd-toggle"
                                        :title="ignore ? '{{ __('Click to unmark exception') }}' : '{{ __('Click to mark as exception') }}'">
                                        <span class="hd-toggle-knob"></span>
                                    </button>

                                    <button type="button" @click="open = !open" style="background: none; border: 0; color: var(--gray-400); cursor: pointer;">
                                        <svg style="width: 1rem; height: 1rem; transition: transform 0.15s;" :style="open ? 'transform: rotate(180deg);' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </div>

                                <div x-show="open" x-cloak style="border-top: 1px solid var(--gray-100); padding: 0.75rem 1rem;">
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;" class="hd-xs hd-mb-3">
                                        <div style="background: var(--purple-50); border: 1px solid var(--purple-100); border-radius: 0.5rem; padding: 0.75rem;">
                                            <p class="hd-fw-semibold" style="color: var(--purple-700); margin: 0 0 0.25rem 0;">{{ __('AI proposal') }}</p>
                                            <p class="hd-dim" style="margin: 0;">Impact: <strong>{{ $log->ai_impact?->value ?? '—' }}</strong></p>
                                            <p class="hd-dim" style="margin: 0;">Labels: <strong>{{ implode(', ', $log->ai_labels ?? []) ?: '—' }}</strong></p>
                                            <p class="hd-muted hd-mt-1" style="margin: 0.25rem 0 0 0;">Skill: {{ $log->ai_skill_version ?? '—' }}</p>
                                        </div>
                                        <div style="background: var(--blue-50); border: 1px solid var(--blue-100); border-radius: 0.5rem; padding: 0.75rem;">
                                            <p class="hd-fw-semibold" style="color: var(--blue-700); margin: 0 0 0.25rem 0;">{{ __('Agent correction') }}</p>
                                            <p class="hd-dim" style="margin: 0;">Impact: <strong>{{ $log->agent_impact?->value ?? '—' }}</strong></p>
                                            <p class="hd-dim" style="margin: 0;">Labels: <strong>{{ implode(', ', $log->agent_labels ?? []) ?: '—' }}</strong></p>
                                            <p class="hd-muted hd-mt-1" style="margin: 0.25rem 0 0 0;">{{ __('By') }}: {{ $log->agent?->name ?? '—' }}</p>
                                        </div>
                                    </div>

                                    <div x-show="ignore" x-cloak>
                                        <form method="POST" action="{{ route('corrections.ignore', $log) }}" class="hd-flex hd-gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="ignore_in_training" value="1">
                                            <input type="text" name="ignore_reason"
                                                value="{{ $log->ignore_reason }}"
                                                placeholder="{{ __('Reason for exception (optional)…') }}"
                                                class="hd-input"
                                                style="flex: 1 1 0;">
                                            <button type="submit" class="hd-btn hd-btn-sm" style="background: var(--amber-100); color: var(--amber-800); border: 1px solid var(--amber-200);">
                                                {{ __('Save') }}
                                            </button>
                                        </form>
                                    </div>

                                    @if($log->ignore_in_training && $log->ignore_reason)
                                        <p class="hd-xs hd-mt-2" style="background: var(--amber-50); border: 1px solid var(--amber-200); border-radius: 0.5rem; padding: 0.5rem 0.75rem; color: var(--amber-700); margin: 0.5rem 0 0 0;">
                                            <strong>{{ __('Reason') }}:</strong> {{ $log->ignore_reason }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="hd-empty">
                                <p style="margin: 0;">{{ __('No corrections yet.') }}</p>
                            </div>
                        @endforelse

                        <div class="hd-empty"
                             x-show="{{ $corrections->count() }} > 0 && Array.from($el.parentElement.querySelectorAll('[data-status]')).every(el => el.style.display === 'none')"
                             style="display: none;">
                            <p style="margin: 0;">{{ __('No corrections for selected statuses.') }}</p>
                        </div>
                    </div>

                    @if($corrections->hasPages())
                        <div class="hd-mt-4" style="padding-top: 1rem; border-top: 1px solid var(--gray-100);">
                            {{ $corrections->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-layouts.ampp>
