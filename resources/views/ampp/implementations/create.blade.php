<x-layouts.ampp :title="__('Manual Registration')">
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ action(\App\Http\Controllers\Ampp\Implementations\IndexImplementationController::class) }}" class="text-muted">
            <i class="fas fa-arrow-left"></i>
        </a>
        <x-ui.page-title>{{ __('Manual Registration') }}</x-ui.page-title>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Implementations\StoreImplementationController::class) }}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-forms.label for="name">{{ __('Name') }} *</x-forms.label>
                                <x-forms.input name="name" required />
                                <x-forms.error-message for="name" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-forms.label for="app_url">{{ __('URL') }}</x-forms.label>
                                <x-forms.input name="app_url" type="url" placeholder="https://" />
                                <x-forms.error-message for="app_url" />
                                <div class="form-text">{{ __('If provided, AMPP will ping this URL for uptime monitoring.') }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <x-forms.label for="tags">{{ __('Tags') }}</x-forms.label>
                                <x-forms.input name="tags" placeholder="{{ __('laravel, client-x, production') }}" />
                                <x-forms.error-message for="tags" />
                                <div class="form-text">{{ __('Comma-separated') }}</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <x-forms.label for="heartbeat_interval">{{ __('Heartbeat interval (seconds)') }}</x-forms.label>
                                <x-forms.input name="heartbeat_interval" type="number" value="60" min="10" max="3600" />
                                <x-forms.error-message for="heartbeat_interval" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <x-forms.label for="notes">{{ __('Notes') }}</x-forms.label>
                            <x-forms.textarea name="notes" rows="3" />
                            <x-forms.error-message for="notes" />
                        </div>

                        <x-forms.submit>{{ __('Create Implementation') }}</x-forms.submit>
                    </x-forms.form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-blue-50">
                <div class="card-body">
                    <h6 class="fw-bold text-blue-800 mb-2">
                        <i class="fas fa-info-circle me-1"></i> {{ __('Manual vs Beacon') }}
                    </h6>
                    <p class="text-blue-700 small mb-2">
                        {{ __('Manual registrations are for projects that don\'t use the beacon package (static sites, WordPress, non-Laravel apps).') }}
                    </p>
                    <p class="text-blue-700 small mb-0">
                        {{ __('For Laravel projects, install the beacon package instead — it auto-registers and reports system info, cron status, and errors.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.ampp>
