<x-layouts.ampp :title="__('Edit') . ' — ' . $implementation->name">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ action(\App\Http\Controllers\Ampp\Implementations\ShowImplementationController::class, $implementation) }}" class="text-muted">
            <i class="fas fa-arrow-left"></i>
        </a>
        <x-ui.page-title>{{ __('Edit') }} — {{ $implementation->name }}</x-ui.page-title>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Implementations\UpdateImplementationController::class, $implementation) }}" method="PUT">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-forms.label for="name">{{ __('Name') }} *</x-forms.label>
                                <x-forms.input name="name" :value="$implementation->name" required />
                                <x-forms.error-message for="name" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-forms.label for="app_url">{{ __('URL') }}</x-forms.label>
                                <x-forms.input name="app_url" type="url" :value="$implementation->app_url" placeholder="https://" />
                                <x-forms.error-message for="app_url" />
                                <div class="form-text">{{ __('If provided, AMPP will ping this URL for uptime monitoring.') }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-forms.label for="tags">{{ __('Tags') }}</x-forms.label>
                                <x-forms.input name="tags" :value="$implementation->tags ? implode(', ', $implementation->tags) : ''" placeholder="{{ __('laravel, client-x, production') }}" />
                                <x-forms.error-message for="tags" />
                                <div class="form-text">{{ __('Comma-separated') }}</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-forms.label for="heartbeat_interval">{{ __('Heartbeat interval (seconds)') }}</x-forms.label>
                                <x-forms.input name="heartbeat_interval" type="number" :value="$implementation->heartbeat_interval ?? 60" min="10" max="3600" />
                                <x-forms.error-message for="heartbeat_interval" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <x-forms.label for="notes">{{ __('Notes') }}</x-forms.label>
                            <x-forms.textarea name="notes" rows="3">{{ $implementation->notes }}</x-forms.textarea>
                            <x-forms.error-message for="notes" />
                        </div>

                        <x-forms.submit>{{ __('Save Changes') }}</x-forms.submit>
                    </x-forms.form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.ampp>
