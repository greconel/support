<x-layouts.ampp :title="__('Implementations')">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <x-ui.page-title>{{ __('Implementations') }}</x-ui.page-title>

        <div class="d-flex gap-2">
            <a href="#keyManagerModal" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-toggle="modal">
                <i class="fas fa-key me-1"></i> {{ __('Manage Keys') }}
            </a>
            <a href="{{ action(\App\Http\Controllers\Ampp\Implementations\CreateImplementationController::class) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fas fa-plus me-1"></i> {{ __('Manual Registration') }}
            </a>
        </div>
    </div>

    {{-- Status overview cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 text-center">
                    <div class="fs-2 fw-bold text-dark">{{ $stats['total'] }}</div>
                    <div class="text-muted small text-uppercase tracking-wide">{{ __('Total') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm" style="border-left: 3px solid #10b981 !important;">
                <div class="card-body p-3 text-center">
                    <div class="fs-2 fw-bold text-green-600">{{ $stats['online'] }}</div>
                    <div class="text-muted small text-uppercase tracking-wide">{{ __('Online') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm" style="border-left: 3px solid #f59e0b !important;">
                <div class="card-body p-3 text-center">
                    <div class="fs-2 fw-bold text-yellow-600">{{ $stats['degraded'] }}</div>
                    <div class="text-muted small text-uppercase tracking-wide">{{ __('Degraded') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm" style="border-left: 3px solid #ef4444 !important;">
                <div class="card-body p-3 text-center">
                    <div class="fs-2 fw-bold text-red-600">{{ $stats['offline'] }}</div>
                    <div class="text-muted small text-uppercase tracking-wide">{{ __('Offline') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Schedule overview cards --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 text-center">
                    <div class="fs-2 fw-bold text-dark">{{ $scheduleStats['total'] }}</div>
                    <div class="text-muted small text-uppercase tracking-wide">{{ __('Tasks') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm" style="border-left: 3px solid #10b981 !important;">
                <div class="card-body p-3 text-center">
                    <div class="fs-2 fw-bold text-green-600">{{ $scheduleStats['on_time'] }}</div>
                    <div class="text-muted small text-uppercase tracking-wide">{{ __('On Time') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm" style="border-left: 3px solid #ef4444 !important;">
                <div class="card-body p-3 text-center">
                    <div class="fs-2 fw-bold text-red-600">{{ $scheduleStats['errored'] }}</div>
                    <div class="text-muted small text-uppercase tracking-wide">{{ __('Errored') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm" style="border-left: 3px solid #f59e0b !important;">
                <div class="card-body p-3 text-center">
                    <div class="fs-2 fw-bold text-yellow-600">{{ $scheduleStats['overdue'] }}</div>
                    <div class="text-muted small text-uppercase tracking-wide">{{ __('Overdue') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Implementation grid --}}
    <div class="row g-3">
        @forelse($implementations as $implementation)
            <div class="col-md-6 col-xl-4">
                <a href="{{ action(\App\Http\Controllers\Ampp\Implementations\ShowImplementationController::class, $implementation) }}"
                   class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 implementation-card">
                        <div class="card-body p-0">
                            {{-- Status bar --}}
                            <div class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom"
                                 style="background: {{ match($implementation->status) {
                                     \App\Enums\ImplementationStatus::Online => 'linear-gradient(135deg, #ecfdf5, #d1fae5)',
                                     \App\Enums\ImplementationStatus::Degraded => 'linear-gradient(135deg, #fffbeb, #fef3c7)',
                                     \App\Enums\ImplementationStatus::Offline => 'linear-gradient(135deg, #fef2f2, #fee2e2)',
                                     default => 'linear-gradient(135deg, #f9fafb, #f3f4f6)',
                                 } }};">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="d-inline-block rounded-circle"
                                          style="width: 10px; height: 10px; background-color: {{ match($implementation->status) {
                                              \App\Enums\ImplementationStatus::Online => '#10b981',
                                              \App\Enums\ImplementationStatus::Degraded => '#f59e0b',
                                              \App\Enums\ImplementationStatus::Offline => '#ef4444',
                                              default => '#9ca3af',
                                          } }};"></span>
                                    <span class="small fw-semibold" style="color: {{ match($implementation->status) {
                                        \App\Enums\ImplementationStatus::Online => '#065f46',
                                        \App\Enums\ImplementationStatus::Degraded => '#92400e',
                                        \App\Enums\ImplementationStatus::Offline => '#991b1b',
                                        default => '#6b7280',
                                    } }};">{{ $implementation->status->label() }}</span>
                                </div>

                                <span class="badge bg-{{ $implementation->type->color() }} bg-opacity-10 text-{{ $implementation->type->color() }}">
                                    {{ $implementation->type->label() }}
                                </span>
                            </div>

                            {{-- Content --}}
                            <div class="p-3">
                                <h6 class="fw-bold text-dark mb-1">{{ $implementation->name }}</h6>

                                @if($implementation->app_url)
                                    <div class="text-muted small mb-2 text-truncate">
                                        <i class="fas fa-globe me-1" style="font-size: 0.7rem;"></i>
                                        {{ parse_url($implementation->app_url, PHP_URL_HOST) }}
                                    </div>
                                @endif

                                {{-- Quick info from latest snapshot --}}
                                @php $snap = $implementation->latest_snapshot_data; @endphp
                                @if(!empty($snap))
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        @if(isset($snap['php']['version']))
                                            <span class="badge bg-gray-100 text-gray-700">
                                                PHP {{ $snap['php']['version'] }}
                                            </span>
                                        @endif
                                        @if(isset($snap['laravel']['version']))
                                            <span class="badge bg-gray-100 text-gray-700">
                                                Laravel {{ $snap['laravel']['version'] }}
                                            </span>
                                        @endif
                                        @if(isset($snap['database']['driver']))
                                            <span class="badge bg-gray-100 text-gray-700">
                                                {{ ucfirst($snap['database']['driver']) }}
                                                @if(isset($snap['database']['size_mb']))
                                                    ({{ number_format($snap['database']['size_mb'], 0) }} MB)
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                {{-- Sub-status indicators --}}
                                @php
                                    $overdueSchedules = $implementation->schedules->filter->is_overdue;
                                    $failedSchedules = $implementation->schedules->filter(fn($s) => $s->last_exit_code !== null && $s->last_exit_code !== 0);
                                    $hasScheduleIssues = $overdueSchedules->isNotEmpty() || $failedSchedules->isNotEmpty();
                                @endphp
                                <div class="d-flex flex-wrap gap-2 pt-2 border-top" style="font-size: 0.75rem;">
                                    {{-- Heartbeat indicator --}}
                                    <span class="d-inline-flex align-items-center gap-1 text-{{ $implementation->status->color() }}">
                                        <i class="fas fa-heartbeat"></i>
                                        @if($implementation->last_heartbeat_at)
                                            {{ $implementation->last_heartbeat_at->diffForHumans() }}
                                        @else
                                            {{ __('No ping') }}
                                        @endif
                                    </span>

                                    {{-- Schedule indicator --}}
                                    @if($implementation->schedules->isNotEmpty())
                                        @if($overdueSchedules->isNotEmpty())
                                            <span class="d-inline-flex align-items-center gap-1 text-danger fw-semibold">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $overdueSchedules->count() }} {{ __('overdue') }}
                                            </span>
                                        @elseif($failedSchedules->isNotEmpty())
                                            <span class="d-inline-flex align-items-center gap-1 text-danger">
                                                <i class="fas fa-times-circle"></i>
                                                {{ $failedSchedules->count() }} {{ __('failed') }}
                                            </span>
                                        @else
                                            <span class="d-inline-flex align-items-center gap-1 text-success">
                                                <i class="fas fa-clock"></i>
                                                {{ __('OK') }}
                                            </span>
                                        @endif
                                    @endif

                                    {{-- Errors indicator --}}
                                    @if($implementation->errors_count > 0)
                                        <span class="d-inline-flex align-items-center gap-1 text-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $implementation->errors_count }} {{ __('errors') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-satellite-dish text-gray-300 mb-3" style="font-size: 3rem;"></i>
                        <p class="text-muted mb-3">{{ __('No implementations registered yet') }}</p>
                        <p class="text-muted small">{{ __('Generate an API key and install the beacon package on a project, or create a manual registration.') }}</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Key Manager Modal --}}
    <div class="modal fade" id="keyManagerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Beacon API Keys') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <livewire:ampp.implementations.key-manager />
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .implementation-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .implementation-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        }
        .tracking-wide {
            letter-spacing: 0.05em;
        }
    </style>
    @endpush
</x-layouts.ampp>
