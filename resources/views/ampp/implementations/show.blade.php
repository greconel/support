<x-layouts.ampp :title="$implementation->name">
    {{-- Header --}}
    <div class="d-flex align-items-start justify-content-between mb-4">
        <div>
            <div class="d-flex align-items-center gap-3 mb-1">
                <a href="{{ action(\App\Http\Controllers\Ampp\Implementations\IndexImplementationController::class) }}" class="text-muted">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <x-ui.page-title>{{ $implementation->name }}</x-ui.page-title>
                <span class="badge bg-{{ $implementation->status->color() }}">
                    {{ $implementation->status->label() }}
                </span>
                <span class="badge bg-{{ $implementation->type->color() }} bg-opacity-25 text-{{ $implementation->type->color() }}">
                    {{ $implementation->type->label() }}
                </span>
            </div>

            @if($implementation->app_url)
                <a href="{{ $implementation->app_url }}" target="_blank" class="text-muted small ms-4 ps-3">
                    <i class="fas fa-external-link-alt me-1"></i> {{ $implementation->app_url }}
                </a>
            @endif
        </div>

        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fas fa-cog me-1"></i> {{ __('Actions') }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @if($implementation->type === \App\Enums\ImplementationType::Manual)
                    <li>
                        <a class="dropdown-item" href="{{ action(\App\Http\Controllers\Ampp\Implementations\EditImplementationController::class, $implementation) }}">
                            <i class="fas fa-pencil-alt me-2 text-primary"></i> {{ __('Edit') }}
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                @endif
                <li>
                    <button class="dropdown-item" onclick="if(confirm('{{ __('Are you sure?') }}')) document.getElementById('deleteForm').submit()">
                        <i class="fas fa-trash me-2 text-danger"></i> {{ __('Archive') }}
                    </button>
                </li>
            </ul>
        </div>

        <form id="deleteForm" method="POST"
              action="{{ action(\App\Http\Controllers\Ampp\Implementations\DestroyImplementationController::class, $implementation) }}"
              class="d-none">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <div class="row g-4">
        {{-- Left column --}}
        <div class="col-lg-8">
            {{-- Uptime card --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">{{ __('Uptime — Last 24 hours') }}</h6>
                        @if($uptimePercentage !== null)
                            <span class="fs-4 fw-bold text-{{ $uptimePercentage >= 99 ? 'green-600' : ($uptimePercentage >= 95 ? 'yellow-600' : 'red-600') }}">
                                {{ $uptimePercentage }}%
                            </span>
                        @else
                            <span class="text-muted">{{ __('No data') }}</span>
                        @endif
                    </div>

                    {{-- 24h timeline with responsive slot sizes --}}
                    @php
                        $now = now();
                        $start = $now->copy()->subHours(24);

                        $buildSlots = function ($slotMinutes) use ($heartbeats, $start) {
                            $slotCount = (int) (24 * 60 / $slotMinutes);
                            $slots = [];
                            for ($i = 0; $i < $slotCount; $i++) {
                                $slotStart = $start->copy()->addMinutes($i * $slotMinutes);
                                $slotEnd = $slotStart->copy()->addMinutes($slotMinutes);

                                $slotBeats = $heartbeats->filter(function ($hb) use ($slotStart, $slotEnd) {
                                    return $hb->created_at->gte($slotStart) && $hb->created_at->lt($slotEnd);
                                });

                                $total = $slotBeats->count();
                                $successes = $slotBeats->where('success', true)->count();
                                $failures = $total - $successes;
                                $avgMs = $total > 0 ? round($slotBeats->avg('response_time_ms')) : null;

                                if ($total === 0) {
                                    $color = '#e5e7eb';
                                    $status = 'No pings';
                                } elseif ($failures === 0) {
                                    $color = '#10b981';
                                    $status = $total . ' OK';
                                } elseif ($successes === 0) {
                                    $color = '#ef4444';
                                    $status = $total . ' failed';
                                } else {
                                    $color = '#f59e0b';
                                    $status = $successes . ' OK, ' . $failures . ' failed';
                                }

                                $slots[] = [
                                    'from' => $slotStart->format('H:i'),
                                    'to' => $slotEnd->format('H:i'),
                                    'color' => $color,
                                    'status' => $status,
                                    'avg_ms' => $avgMs,
                                    'total' => $total,
                                ];
                            }
                            return $slots;
                        };

                        // 15-min slots (96 bars) for desktop, 30-min (48) for tablet, 60-min (24) for mobile
                        $slotSets = [
                            ['minutes' => 15, 'slots' => $buildSlots(15)],
                            ['minutes' => 30, 'slots' => $buildSlots(30)],
                            ['minutes' => 60, 'slots' => $buildSlots(60)],
                        ];
                    @endphp

                    @if($heartbeats->isEmpty())
                        <div class="text-muted small py-2">{{ __('No heartbeat data yet') }}</div>
                    @else
                        <div class="uptime-timeline-wrapper" style="position: relative;">
                            @php $rectWidth = 10; $rectGap = 2; $rectHeight = 34; @endphp
                            @foreach($slotSets as $set)
                                @php
                                    $count = count($set['slots']);
                                    $svgWidth = ($rectWidth * $count) + ($rectGap * ($count - 1));
                                @endphp
                                <svg class="uptime-timeline-svg" data-slot-minutes="{{ $set['minutes'] }}" viewBox="0 0 {{ $svgWidth }} {{ $rectHeight }}" preserveAspectRatio="none" style="width: 100%; height: 34px; display: none; border-radius: 3px;">
                                    @foreach($set['slots'] as $idx => $slot)
                                        <rect
                                            x="{{ $idx * ($rectWidth + $rectGap) }}"
                                            y="0"
                                            width="{{ $rectWidth }}"
                                            height="{{ $rectHeight }}"
                                            rx="2"
                                            fill="{{ $slot['color'] }}"
                                            class="uptime-bar"
                                            data-from="{{ $slot['from'] }}"
                                            data-to="{{ $slot['to'] }}"
                                            data-status="{{ $slot['status'] }}"
                                            data-avg="{{ $slot['avg_ms'] }}"
                                        />
                                    @endforeach
                                </svg>
                            @endforeach

                            {{-- Tooltip --}}
                            <div id="uptime-tooltip" style="display: none; position: absolute; top: -45px; background: #1f2937; color: #fff; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; white-space: nowrap; pointer-events: none; z-index: 10; transform: translateX(-50%);">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <span class="text-muted" style="font-size: 0.7rem;">{{ $start->format('H:i') }}</span>
                            <span class="text-muted" style="font-size: 0.7rem;">{{ $now->format('H:i') }}</span>
                        </div>

                        {{-- Legend --}}
                        <div class="d-flex gap-3 mt-2" style="font-size: 0.7rem;">
                            <span class="d-flex align-items-center gap-1">
                                <span style="width: 10px; height: 10px; border-radius: 2px; background: #10b981; display: inline-block;"></span>
                                <span class="text-muted">{{ __('OK') }}</span>
                            </span>
                            <span class="d-flex align-items-center gap-1">
                                <span style="width: 10px; height: 10px; border-radius: 2px; background: #f59e0b; display: inline-block;"></span>
                                <span class="text-muted">{{ __('Mixed') }}</span>
                            </span>
                            <span class="d-flex align-items-center gap-1">
                                <span style="width: 10px; height: 10px; border-radius: 2px; background: #ef4444; display: inline-block;"></span>
                                <span class="text-muted">{{ __('Failed') }}</span>
                            </span>
                            <span class="d-flex align-items-center gap-1">
                                <span style="width: 10px; height: 10px; border-radius: 2px; background: #e5e7eb; display: inline-block;"></span>
                                <span class="text-muted">{{ __('No data') }}</span>
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Scheduled Tasks --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">{{ __('Scheduled Tasks') }}</h6>
                    <span class="badge bg-gray-200 text-gray-700">{{ $implementation->schedules->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <livewire:ampp.implementations.schedule-list :implementation="$implementation" />

                    @if($implementation->type === \App\Enums\ImplementationType::Manual)
                        <livewire:ampp.implementations.manual-schedule-manager :implementation="$implementation" />
                    @endif
                </div>
            </div>

            {{-- Recent Errors --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">{{ __('Recent Errors') }}</h6>
                    <span class="badge bg-{{ $recentErrors->count() > 0 ? 'danger' : 'gray-200' }} {{ $recentErrors->count() > 0 ? '' : 'text-gray-700' }}">
                        {{ $recentErrors->count() }}
                    </span>
                </div>
                <div class="card-body p-0">
                    @forelse($recentErrors as $error)
                        <div class="px-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}" x-data="{ open: false }">
                            <div class="d-flex justify-content-between align-items-start cursor-pointer" @click="open = !open">
                                <div class="d-flex gap-2 flex-grow-1 min-width-0">
                                    <span class="badge bg-{{ $error->level->color() }} align-self-start mt-1" style="font-size: 0.65rem;">
                                        {{ $error->level->label() }}
                                    </span>
                                    <div class="min-width-0">
                                        <div class="text-dark text-truncate fw-medium" style="font-size: 0.85rem;">
                                            {{ $error->message }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.7rem;">
                                            @if($error->exception_class)
                                                {{ class_basename($error->exception_class) }}
                                            @endif
                                            @if($error->file)
                                                — {{ basename($error->file) }}:{{ $error->line }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="text-muted text-nowrap ms-3" style="font-size: 0.7rem;">
                                    {{ $error->occurred_at->diffForHumans() }}
                                    <i class="fas fa-chevron-down ms-1" :class="{ 'fa-chevron-up': open }"></i>
                                </div>
                            </div>

                            <div x-show="open" x-cloak class="mt-2">
                                @if($error->trace)
                                    <pre class="bg-gray-50 p-2 rounded small overflow-auto" style="max-height: 200px; font-size: 0.7rem;">{{ $error->trace }}</pre>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-3 text-center text-muted small">
                            {{ __('No recent errors') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right column: System info --}}
        <div class="col-lg-4">
            @php $snap = $implementation->latest_snapshot_data; @endphp

            {{-- Quick stats --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="fw-bold mb-0">{{ __('System Info') }}</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @if(isset($snap['php']['version']))
                            <div class="list-group-item d-flex justify-content-between py-2">
                                <span class="text-muted small">PHP</span>
                                <span class="fw-medium small">{{ $snap['php']['version'] }}</span>
                            </div>
                        @endif
                        @if(isset($snap['laravel']['version']))
                            <div class="list-group-item d-flex justify-content-between py-2">
                                <span class="text-muted small">Laravel</span>
                                <span class="fw-medium small">{{ $snap['laravel']['version'] }}</span>
                            </div>
                        @endif
                        @if(isset($snap['laravel']['environment']))
                            <div class="list-group-item d-flex justify-content-between py-2">
                                <span class="text-muted small">{{ __('Environment') }}</span>
                                <span class="fw-medium small">
                                    <span class="badge bg-{{ $snap['laravel']['environment'] === 'production' ? 'success' : 'warning' }}">
                                        {{ $snap['laravel']['environment'] }}
                                    </span>
                                </span>
                            </div>
                        @endif
                        @if(isset($snap['laravel']['debug']))
                            <div class="list-group-item d-flex justify-content-between py-2">
                                <span class="text-muted small">Debug</span>
                                <span class="fw-medium small">
                                    @if($snap['laravel']['debug'])
                                        <span class="badge bg-danger">ON</span>
                                    @else
                                        <span class="badge bg-success">OFF</span>
                                    @endif
                                </span>
                            </div>
                        @endif
                        @if(isset($snap['database']['driver']))
                            <div class="list-group-item d-flex justify-content-between py-2">
                                <span class="text-muted small">{{ __('Database') }}</span>
                                <span class="fw-medium small">
                                    {{ ucfirst($snap['database']['driver']) }}
                                    @if(isset($snap['database']['version']))
                                        {{ $snap['database']['version'] }}
                                    @endif
                                </span>
                            </div>
                        @endif
                        @if(isset($snap['database']['size_mb']))
                            <div class="list-group-item d-flex justify-content-between py-2">
                                <span class="text-muted small">{{ __('DB Size') }}</span>
                                <span class="fw-medium small">{{ number_format($snap['database']['size_mb'], 0) }} MB</span>
                            </div>
                        @endif
                        @if(isset($snap['server']['hostname']))
                            <div class="list-group-item d-flex justify-content-between py-2">
                                <span class="text-muted small">{{ __('Server') }}</span>
                                <span class="fw-medium small">{{ $snap['server']['hostname'] }}</span>
                            </div>
                        @endif
                        @if(isset($snap['server']['ip']))
                            <div class="list-group-item d-flex justify-content-between py-2">
                                <span class="text-muted small">IP</span>
                                <span class="fw-medium small font-monospace">{{ $snap['server']['ip'] }}</span>
                            </div>
                        @endif
                        @if(isset($snap['deploy']['commit']))
                            <div class="list-group-item d-flex justify-content-between py-2">
                                <span class="text-muted small">{{ __('Deploy') }}</span>
                                <span class="fw-medium small font-monospace">
                                    {{ $snap['deploy']['commit'] }}
                                    @if(isset($snap['deploy']['deployed_at']))
                                        <br>
                                        <span class="text-muted fw-normal">{{ \Carbon\Carbon::parse($snap['deploy']['deployed_at'])->diffForHumans() }}</span>
                                    @endif
                                </span>
                            </div>
                        @endif

                        {{-- Empty state if no snapshot --}}
                        @if(empty($snap))
                            <div class="list-group-item text-center text-muted small py-3">
                                @if($implementation->type === \App\Enums\ImplementationType::Manual)
                                    {{ __('No system info available for manual implementations.') }}
                                @else
                                    {{ __('Waiting for first data push...') }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Storage --}}
            @if(isset($snap['storage']) || isset($snap['server']['disk_free_gb']))
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="fw-bold mb-0">{{ __('Storage') }}</h6>
                    </div>
                    <div class="card-body">
                        @if(isset($snap['server']['disk_total_gb']) && isset($snap['server']['disk_free_gb']))
                            @php
                                $usedGb = $snap['server']['disk_total_gb'] - $snap['server']['disk_free_gb'];
                                $usedPct = $snap['server']['disk_total_gb'] > 0
                                    ? round(($usedGb / $snap['server']['disk_total_gb']) * 100)
                                    : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small text-muted">{{ __('Disk') }}</span>
                                    <span class="small fw-medium">{{ number_format($usedGb, 1) }} / {{ number_format($snap['server']['disk_total_gb'], 1) }} GB</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-{{ $usedPct > 90 ? 'danger' : ($usedPct > 75 ? 'warning' : 'primary') }}"
                                         style="width: {{ $usedPct }}%"></div>
                                </div>
                            </div>
                        @endif

                        @if(isset($snap['storage']['logs_size_mb']))
                            <div class="d-flex justify-content-between">
                                <span class="small text-muted">{{ __('Logs') }}</span>
                                <span class="small fw-medium {{ $snap['storage']['logs_size_mb'] > 500 ? 'text-danger' : '' }}">
                                    {{ number_format($snap['storage']['logs_size_mb'], 0) }} MB
                                </span>
                            </div>
                        @endif
                        @if(isset($snap['storage']['storage_size_mb']))
                            <div class="d-flex justify-content-between mt-1">
                                <span class="small text-muted">{{ __('Storage folder') }}</span>
                                <span class="small fw-medium">{{ number_format($snap['storage']['storage_size_mb'], 0) }} MB</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Drivers --}}
            @if(isset($snap['laravel']['drivers']))
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="fw-bold mb-0">{{ __('Drivers') }}</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($snap['laravel']['drivers'] as $driver => $value)
                                <div class="list-group-item d-flex justify-content-between py-2">
                                    <span class="text-muted small text-capitalize">{{ $driver }}</span>
                                    <span class="fw-medium small">{{ $value }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tags & Notes --}}
            @if($implementation->tags || $implementation->notes)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        @if($implementation->tags)
                            <div class="mb-2">
                                @foreach($implementation->tags as $tag)
                                    <span class="badge bg-blue-100 text-blue-700 me-1">{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                        @if($implementation->notes)
                            <p class="text-muted small mb-0">{{ $implementation->notes }}</p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Timestamps --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small text-muted">{{ __('Last heartbeat') }}</span>
                        <span class="small">{{ $implementation->last_heartbeat_at?->diffForHumans() ?? __('Never') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small text-muted">{{ __('Last data push') }}</span>
                        <span class="small">{{ $implementation->last_push_at?->diffForHumans() ?? __('Never') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="small text-muted">{{ __('Registered') }}</span>
                        <span class="small">{{ $implementation->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (el) {
                return new bootstrap.Tooltip(el);
            });

            // Responsive uptime timeline
            var svgs = document.querySelectorAll('.uptime-timeline-svg');
            var tooltip = document.getElementById('uptime-tooltip');
            var wrapper = document.querySelector('.uptime-timeline-wrapper');

            function getSlotMinutes() {
                var w = window.innerWidth;
                if (w <= 576) return 60;   // mobile: 24 bars (hourly)
                if (w <= 992) return 30;   // tablet: 48 bars (half-hourly)
                return 15;                 // desktop: 96 bars (15 min)
            }

            function showActiveSvg() {
                var minutes = getSlotMinutes();
                svgs.forEach(function(svg) {
                    svg.style.display = parseInt(svg.dataset.slotMinutes) === minutes ? 'block' : 'none';
                });
            }

            function bindTooltips() {
                if (!tooltip || !wrapper) return;

                wrapper.querySelectorAll('.uptime-bar').forEach(function(rect) {
                    rect.style.cursor = 'pointer';

                    rect.addEventListener('mouseenter', function() {
                        var from = this.getAttribute('data-from');
                        var to = this.getAttribute('data-to');
                        var status = this.getAttribute('data-status');
                        var avg = this.getAttribute('data-avg');

                        var html = '<strong>' + from + ' – ' + to + '</strong> &middot; ' + status;
                        if (avg && avg !== '') {
                            html += ' &middot; ' + avg + 'ms';
                        }

                        tooltip.innerHTML = html;
                        tooltip.style.display = 'block';

                        var wrapperRect = wrapper.getBoundingClientRect();
                        var barRect = this.getBoundingClientRect();
                        var left = (barRect.left + barRect.width / 2) - wrapperRect.left;
                        tooltip.style.left = left + 'px';

                        this.style.opacity = '0.7';
                    });

                    rect.addEventListener('mouseleave', function() {
                        tooltip.style.display = 'none';
                        this.style.opacity = '1';
                    });
                });
            }

            showActiveSvg();
            bindTooltips();
            window.addEventListener('resize', showActiveSvg);
        });
    </script>
    @endpush
</x-layouts.ampp>
