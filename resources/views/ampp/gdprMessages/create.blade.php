<x-layouts.ampp :title="__('Create new GDPR message')" :breadcrumbs="Breadcrumbs::render('createGdprMessage')">
    <div class="container">
        <x-ui.page-title>{{ __('Create new GDPR message') }}</x-ui.page-title>

        <div class="card card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\GdprMessages\StoreGdprMessageController::class) }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="when">{{ __('When did the leak take place') }}</x-forms.label>
                        <x-forms.input name="when" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <x-forms.label for="description">{{ __('Brief description of the leak') }}</x-forms.label>
                        <x-forms.textarea name="description" required></x-forms.textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="what">{{ __('What happened to the data') }}</x-forms.label>
                        <x-forms.input name="what" required />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="amount_of_details">{{ __('How much data has been leaked') }}</x-forms.label>
                        <x-forms.input name="amount_of_details" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="category">{{ __('From which category of people was the data leaked') }}</x-forms.label>
                        <x-forms.input name="category" required />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="type">{{ __('What kind of data') }}</x-forms.label>
                        <x-forms.input name="type" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="consequences">{{ __('Consequences of the infringement') }}</x-forms.label>
                        <x-forms.input name="consequences" required />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="measures">{{ __('Measures taken (both damage limitation and preventive)') }}</x-forms.label>
                        <x-forms.input name="measures" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="notification_requirements">{{ __('When the reporting obligation has been met and if not, why') }}</x-forms.label>
                        <x-forms.input name="notification_requirements" required />
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Create') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>
</x-layouts.ampp>
