<x-layouts.ampp :title="__('Overview') . ' - Helpdesk'">
    @include('ampp.helpdesk.partials.styles')

    <div class="hd-scope">
        <div class="hd-page">

            <h1 class="hd-h1 hd-mb-4">{{ __('Overview') }}</h1>

            {{-- Ticket statistieken --}}
            <div class="hd-cols-3 hd-mb-5" style="margin-bottom: 1.5rem;">
                <a href="{{ route('tickets.index') }}" class="hd-card hd-card-link">
                    <div class="hd-flex hd-items-center hd-gap-4">
                        <div class="hd-icon-bubble orange">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="hd-sm hd-muted" style="margin: 0;">{{ __('Total tickets') }}</p>
                            <p class="hd-fw-semibold" style="font-size: 1.5rem; margin: 0; color: var(--gray-900);">{{ $totalTickets }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('tickets.index', ['status' => 'open']) }}" class="hd-card hd-card-link">
                    <div class="hd-flex hd-items-center hd-gap-4">
                        <div class="hd-icon-bubble green">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="hd-sm hd-muted" style="margin: 0;">{{ __('Open tickets') }}</p>
                            <p class="hd-fw-semibold" style="font-size: 1.5rem; margin: 0; color: var(--gray-900);">{{ $openTickets }}</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('tickets.index', ['status' => 'closed']) }}" class="hd-card hd-card-link">
                    <div class="hd-flex hd-items-center hd-gap-4">
                        <div class="hd-icon-bubble blue">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="hd-sm hd-muted" style="margin: 0;">{{ __('Closed tickets') }}</p>
                            <p class="hd-fw-semibold" style="font-size: 1.5rem; margin: 0; color: var(--gray-900);">{{ $closedTickets }}</p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- AI Correcties header --}}
            <div class="hd-flex hd-items-center hd-justify-between hd-wrap hd-gap-3 hd-mb-4" style="margin-top: 1.5rem;">
                <div class="hd-flex hd-items-center hd-gap-3">
                    <h2 class="hd-h2" style="margin: 0;">{{ __('AI Corrections') }}</h2>
                    <span class="hd-xs hd-muted">
                        {{ __('Skill version') }}:
                        <span class="hd-fw-semibold" style="color: var(--purple-700);">{{ $currentSkillVersion }}</span>
                    </span>
                </div>

                <div class="hd-flex hd-items-center hd-gap-2">
                    @can('manage ai skill')
                        <a href="{{ route('ai-skill.index') }}" class="hd-btn hd-btn-ghost">
                            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.347.347a5 5 0 01-1.651.928l-1.39.39A2 2 0 019.56 18h-.12a2 2 0 01-1.907-1.383l-.39-1.39a5 5 0 01.928-1.651l.347-.347z"/>
                            </svg>
                            {{ __('Manage skill') }}
                        </a>
                    @endcan

                    @can('export ai corrections')
                        <a href="{{ route('corrections.export') }}" class="hd-btn hd-btn-ghost">
                            <svg style="width:1rem;height:1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            {{ __('AI corrections export') }}
                        </a>
                    @endcan
                </div>
            </div>

            {{-- AI correctie stats --}}
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;" class="hd-correction-stats hd-mb-4">
                <div class="hd-card hd-card-tight">
                    <div class="hd-flex hd-items-center hd-gap-3">
                        <div class="hd-icon-bubble purple" style="width: 2.25rem; height: 2.25rem;">
                            <svg style="width: 1.125rem; height: 1.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="hd-xs hd-muted" style="margin: 0;">{{ __('Total') }}</p>
                            <p class="hd-fw-semibold" style="font-size: 1.25rem; margin: 0;">{{ $totalCorrections }}</p>
                        </div>
                    </div>
                </div>

                <div class="hd-card hd-card-tight">
                    <div class="hd-flex hd-items-center hd-gap-3">
                        <div class="hd-icon-bubble amber" style="width: 2.25rem; height: 2.25rem;">
                            <svg style="width: 1.125rem; height: 1.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="hd-xs hd-muted" style="margin: 0;">{{ __('Unprocessed') }}</p>
                            <p class="hd-fw-semibold" style="font-size: 1.25rem; margin: 0;">{{ $unprocessed }}</p>
                        </div>
                    </div>
                </div>

                <div class="hd-card hd-card-tight">
                    <div class="hd-flex hd-items-center hd-gap-3">
                        <div class="hd-icon-bubble blue" style="width: 2.25rem; height: 2.25rem;">
                            <svg style="width: 1.125rem; height: 1.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="hd-xs hd-muted" style="margin: 0;">{{ __('Impact only') }}</p>
                            <p class="hd-fw-semibold" style="font-size: 1.25rem; margin: 0;">{{ $impactOnly }}</p>
                        </div>
                    </div>
                </div>

                <div class="hd-card hd-card-tight">
                    <div class="hd-flex hd-items-center hd-gap-3">
                        <div class="hd-icon-bubble" style="width: 2.25rem; height: 2.25rem; background: var(--violet-100); color: var(--violet-700);">
                            <svg style="width: 1.125rem; height: 1.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="hd-xs hd-muted" style="margin: 0;">{{ __('Labels only') }}</p>
                            <p class="hd-fw-semibold" style="font-size: 1.25rem; margin: 0;">{{ $labelsOnly }}</p>
                        </div>
                    </div>
                </div>

                <div class="hd-card hd-card-tight">
                    <div class="hd-flex hd-items-center hd-gap-3">
                        <div class="hd-icon-bubble" style="width: 2.25rem; height: 2.25rem; background: var(--red-100); color: var(--red-600);">
                            <svg style="width: 1.125rem; height: 1.125rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="hd-xs hd-muted" style="margin: 0;">{{ __('Impact + labels') }}</p>
                            <p class="hd-fw-semibold" style="font-size: 1.25rem; margin: 0;">{{ $both }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent corrections --}}
            <div class="hd-card">
                <h3 class="hd-h2 hd-mb-4" style="margin-top: 0;">{{ __('Recent corrections') }}</h3>

                @if($recentCorrections->isEmpty())
                    <div class="hd-empty">
                        <p style="margin: 0;">{{ __('No corrections yet.') }}</p>
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @foreach($recentCorrections as $log)
                            @php
                                $typeBadge = [
                                    'impact_only' => ['hd-badge-soft-blue', __('Impact only')],
                                    'labels_only' => ['hd-badge-soft-violet', __('Labels only')],
                                    'both' => ['hd-badge-soft-red', __('Impact + labels')],
                                ][$log->correction_type?->value] ?? ['hd-badge-soft-gray', $log->correction_type?->value];
                            @endphp
                            <div class="hd-flex hd-items-center hd-gap-3" style="padding: 0.5rem 0.75rem; border: 1px solid var(--gray-100); border-radius: 0.5rem;">
                                <div style="flex: 1 1 0; min-width: 0;">
                                    <div class="hd-flex hd-items-center hd-gap-2 hd-wrap">
                                        <a href="{{ route('tickets.show', $log->ticket_id) }}" class="hd-text-link hd-xs hd-fw-semibold">
                                            {{ $log->ticket?->ticket_number ?? '#?' }}
                                        </a>
                                        <span class="hd-xs hd-dim" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ \Illuminate\Support\Str::limit($log->ticket_subject, 60) }}
                                        </span>
                                    </div>
                                    <div class="hd-flex hd-items-center hd-gap-2 hd-wrap hd-mt-1">
                                        <span class="hd-badge {{ $typeBadge[0] }}" style="font-size: 10px;">{{ $typeBadge[1] }}</span>
                                        @if($log->processed)
                                            <span class="hd-badge hd-badge-soft-emerald" style="font-size: 10px;">{{ __('Processed') }}</span>
                                        @elseif($log->ignore_in_training)
                                            <span class="hd-badge hd-badge-soft-amber" style="font-size: 10px;">{{ __('Ignored') }}</span>
                                        @else
                                            <span class="hd-badge hd-badge-soft-gray" style="font-size: 10px;">{{ __('Pending') }}</span>
                                        @endif
                                        <span class="hd-xs hd-subtle">
                                            {{ $log->created_at->diffForHumans() }}
                                            @if($log->agent) · {{ $log->agent->name }} @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    @once
        @push('styles')
            <style>
                @media (max-width: 767px) {
                    .hd-correction-stats { grid-template-columns: 1fr !important; }
                }
                @media (min-width: 992px) {
                    .hd-correction-stats { grid-template-columns: repeat(5, 1fr) !important; }
                }
            </style>
        @endpush
    @endonce
</x-layouts.ampp>
