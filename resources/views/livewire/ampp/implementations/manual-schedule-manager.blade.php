<div>
    {{-- Add new schedule form --}}
    <div class="px-3 py-3 bg-gray-50 border-top">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-medium mb-1">{{ __('Command') }}</label>
                <input type="text" class="form-control form-control-sm" wire:model.defer="command" placeholder="backup:run">
                @error('command') <span class="text-danger" style="font-size: 0.7rem;">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-medium mb-1">{{ __('Cron expression') }}</label>
                <input type="text" class="form-control form-control-sm" wire:model.defer="expression" placeholder="0 3 * * *">
                @error('expression') <span class="text-danger" style="font-size: 0.7rem;">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium mb-1">{{ __('Description') }}</label>
                <input type="text" class="form-control form-control-sm" wire:model.defer="description" placeholder="{{ __('Optional') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium mb-1">{{ __('Timezone') }}</label>
                <input type="text" class="form-control form-control-sm" wire:model.defer="timezone">
            </div>
            <div class="col-md-1">
                <label class="form-label small fw-medium mb-1">{{ __('Grace (min)') }}</label>
                <input type="number" class="form-control form-control-sm" wire:model.defer="gracePeriod" placeholder="{{ config('beacon.schedule_grace_period', 5) }}">
            </div>
            <div class="col-md-1">
                <label class="form-label small fw-medium mb-1">&nbsp;</label>
                <button class="btn btn-primary btn-sm w-100" wire:click="addSchedule">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
    </div>
</div>
