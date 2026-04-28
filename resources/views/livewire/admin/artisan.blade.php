<div>
    <x-ui.session-alert session="error_artisan" class="alert-danger" />

    <div class="row">
        <div class="col-12 mb-3">
            <x-forms.label for="command">{{ __('Command') }}</x-forms.label>
            <div class="input-group has-validation mb-3">
                <span class="input-group-text" id="artisanPre">php artisan</span>

                <input type="text" name="command"
                       class="form-control {{ $errors->has('command') ? 'is-invalid' : null }}"
                       aria-label="command" aria-describedby="artisanPre"
                       list="commandOptions"
                       wire:model.lazy="command">

                <datalist id="commandOptions">
                    <option value="migrate">
                    <option value="config:cache">
                    <option value="cache:clear">
                    <option value="route:clear">
                    <option value="view:clear">
                    <option value="optimize">
                    <option value="storage:link">
                </datalist>

                <x-forms.submit class="btn btn-warning" wire:click="run" wire:target="run" wire:loading.attr="disabled">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading="run"></span>
                    <span wire:loading.remove="run">{{ __('Run') }}</span>
                </x-forms.submit>

                @error('command')
                <div id="artisanPre" class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>
    </div>

    <p class="text-muted mb-1">{{ __('Output') }}</p>

    <div class="bg-secondary-light rounded-lg p-3" style="max-height: 500px; overflow-y: scroll" wire:ignore.self>
        {!! $output !!}
    </div>
</div>
