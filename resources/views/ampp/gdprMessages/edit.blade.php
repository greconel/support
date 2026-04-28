<x-layouts.ampp :title="__('Edit GDPR message')" :breadcrumbs="Breadcrumbs::render('editGdprMessage', $message)">
    <div class="container">
        <x-ui.page-title>{{ __('Edit GDPR message') }}</x-ui.page-title>

        <div class="card card-body">
            <x-ui.session-alert session="address_error" class="alert-danger" />

            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\GdprMessages\UpdateGdprMessageController::class, $message) }}" method="patch">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="when">{{ __('When did the leak take place') }}</x-forms.label>
                        <x-forms.input name="when" :value="$message->when" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <x-forms.label for="description">{{ __('Brief description of the leak') }}</x-forms.label>
                        <x-forms.textarea name="description" required>{{ $message->description }}</x-forms.textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="what">{{ __('What happened to the data') }}</x-forms.label>
                        <x-forms.input name="what" :value="$message->what" required />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="amount_of_details">{{ __('How much data has been leaked') }}</x-forms.label>
                        <x-forms.input name="amount_of_details" :value="$message->amount_of_details" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="category">{{ __('From which category of people was the data leaked') }}</x-forms.label>
                        <x-forms.input name="category" :value="$message->category" required />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="type">{{ __('What kind of data') }}</x-forms.label>
                        <x-forms.input name="type" :value="$message->type" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="consequences">{{ __('Consequences of the infringement') }}</x-forms.label>
                        <x-forms.input name="consequences" :value="$message->consequences" required />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="measures">{{ __('Measures taken (both damage limitation and preventive)') }}</x-forms.label>
                        <x-forms.input name="measures" :value="$message->measures" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="notification_requirements">{{ __('When the reporting obligation has been met and if not, why') }}</x-forms.label>
                        <x-forms.input name="notification_requirements" :value="$message->notification_requirements" required />
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a  href="#confirmDeleteModal" data-bs-toggle="modal" class="link-secondary">
                        {{ __('Delete') }}
                    </a>

                    <x-forms.submit>{{ __('Edit') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>

    <x-push name="modals">
        <x-ui.confirmation-modal id="confirmDeleteModal">
            <x-slot name="content">
                {{ __('Are you sure you want to delete this GDPR message? This action can not be reverted!') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Ampp\GdprMessages\DestroyGdprMessageController::class, $message)" method="delete">
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
