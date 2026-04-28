<div>
    @forelse($schedules as $schedule)
        <div class="d-flex align-items-center justify-content-between px-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
            @if($editingId === $schedule->id)
                {{-- Edit mode --}}
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <code class="text-dark">{{ $schedule->command }}</code>
                        <code class="text-muted">{{ $schedule->expression }}</code>
                    </div>
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small fw-medium mb-1">{{ __('Description') }}</label>
                            <input type="text" class="form-control form-control-sm" wire:model.defer="editDescription" placeholder="{{ __('Optional') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-medium mb-1">{{ __('Grace period (minutes)') }}</label>
                            <input type="number" class="form-control form-control-sm" wire:model.defer="editGracePeriod" placeholder="{{ config('beacon.schedule_grace_period', 5) }}" min="1" max="1440">
                            <div class="form-text" style="font-size: 0.65rem;">{{ __('Default: :min min', ['min' => config('beacon.schedule_grace_period', 5)]) }}</div>
                        </div>
                        <div class="col-md-4 d-flex gap-1">
                            <button class="btn btn-sm btn-primary" wire:click="save">
                                <i class="fas fa-check me-1"></i> {{ __('Save') }}
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" wire:click="cancelEdit">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </div>
                </div>
            @else
                {{-- View mode --}}
                <div class="d-flex align-items-center gap-3">
                    <div>
                        @if($schedule->last_finished_at === null)
                            <span class="d-inline-block rounded-circle bg-gray-400" style="width: 8px; height: 8px;" title="{{ __('Never ran') }}"></span>
                        @elseif($schedule->is_overdue)
                            <span class="d-inline-block rounded-circle bg-danger" style="width: 8px; height: 8px;" title="{{ __('Overdue') }}"></span>
                        @elseif($schedule->last_exit_code === 0)
                            <span class="d-inline-block rounded-circle bg-success" style="width: 8px; height: 8px;"></span>
                        @else
                            <span class="d-inline-block rounded-circle bg-warning" style="width: 8px; height: 8px;"></span>
                        @endif
                    </div>
                    <div>
                        <div class="fw-semibold text-dark" style="font-size: 0.85rem;">
                            <code class="text-dark">{{ $schedule->command }}</code>
                        </div>
                        <div class="text-muted" style="font-size: 0.75rem;">
                            <code>{{ $schedule->expression }}</code>
                            @if($schedule->description)
                                — {{ $schedule->description }}
                            @endif
                            @if($schedule->grace_period_minutes)
                                <span class="ms-1 badge bg-gray-100 text-gray-600" style="font-size: 0.65rem;">
                                    {{ __(':min min grace', ['min' => $schedule->grace_period_minutes]) }}
                                </span>
                            @endif
                        </div>
                        @if($this->implementation->type === \App\Enums\ImplementationType::Manual)
                            <div class="text-muted mt-1" style="font-size: 0.7rem;">
                                <i class="fas fa-link me-1"></i>
                                Ping URL: <code class="user-select-all">{{ url('/api/beacon/ping/' . $schedule->ping_token) }}</code>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-1 align-items-center">
                    @if($schedule->last_finished_at)
                        <span class="text-muted me-2" style="font-size: 0.75rem;">
                            {{ $schedule->last_finished_at->diffForHumans() }}
                            @if($schedule->latestExecution && $schedule->latestExecution->duration_ms)
                                <br><span style="font-size: 0.7rem;">{{ number_format($schedule->latestExecution->duration_ms / 1000, 1) }}s</span>
                            @endif
                        </span>
                    @else
                        <span class="text-muted me-2 small">{{ __('Never') }}</span>
                    @endif
                    <button class="btn btn-sm btn-outline-secondary py-0 px-1"
                            wire:click="edit({{ $schedule->id }})"
                            title="{{ __('Edit') }}">
                        <i class="fas fa-pencil-alt" style="font-size: 0.7rem;"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-secondary py-0 px-1"
                            wire:click="toggleActive({{ $schedule->id }})"
                            title="{{ $schedule->is_active ? __('Pause') : __('Resume') }}">
                        <i class="fas fa-{{ $schedule->is_active ? 'pause' : 'play' }}" style="font-size: 0.7rem;"></i>
                    </button>
                    @if($this->implementation->type === \App\Enums\ImplementationType::Manual)
                        <button class="btn btn-sm btn-outline-danger py-0 px-1"
                                wire:click="deleteSchedule({{ $schedule->id }})"
                                onclick="return confirm('{{ __('Remove this schedule?') }}')"
                                title="{{ __('Remove') }}">
                            <i class="fas fa-trash" style="font-size: 0.7rem;"></i>
                        </button>
                    @endif
                </div>
            @endif
        </div>
    @empty
        <div class="p-3 text-center text-muted small">
            {{ __('No scheduled tasks registered') }}
        </div>
    @endforelse
</div>
