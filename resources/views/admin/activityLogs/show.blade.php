<x-layouts.ampp :title="__('Activity log overview')" :breadcrumbs="Breadcrumbs::render('showActivityLog', $activityLog)">
    <div class="container">
        <x-ui.page-title>{{ __('Activity log overview') }}</x-ui.page-title>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Overview') }}
                    </div>

                    <div class="card-body">
                        <div class="data-row">
                            <span>{{ __('Log name') }}</span>
                            {{ $activityLog->log_name }}
                        </div>

                        <div class="data-row description">
                            <span>{{ __('Description') }}</span>
                            {{ $activityLog->description }}
                        </div>

                        <div class="data-row">
                            <span>{{ __('Subject type') }}</span>
                            {{ $activityLog->subject_type }}
                        </div>

                        <div class="data-row">
                            <span>{{ __('Subject id') }}</span>
                            {{ $activityLog->subject_id }}
                        </div>

                        <div class="data-row">
                            <span>{{ __('Causer type') }}</span>
                            {{ $activityLog->causer_type }}
                        </div>

                        <div class="data-row">
                            <span>{{ __('Causer id') }}</span>
                            {{ $activityLog->causer_id }}
                        </div>

                        <div class="data-row">
                            <span>{{ __('Created at') }}</span>
                            {{ $activityLog->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('Properties') }}
                    </div>

                    <div class="card-body">
                        <pre style="font-size: 120%;"><code class="language-php">{{ print_r($activityLog->properties->toArray(), true) }}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-push name="styles">
        <link rel="stylesheet" href="{{ asset('vendor/prism/prism.css') }}">
    </x-push>

    <x-push name="styles">
        <script src="{{ asset('vendor/prism/prism.js') }}" defer></script>
    </x-push>
</x-layouts.ampp>
