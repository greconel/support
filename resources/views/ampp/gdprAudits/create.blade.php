<x-layouts.ampp :title="__('Create new GDPR audit')" :breadcrumbs="Breadcrumbs::render('createGdprAudit')">
    <div class="container">
        <x-ui.page-title>{{ __('Create new GDPR audit') }}</x-ui.page-title>

        <div class="card card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\GdprAudits\StoreGdprAuditController::class) }}">
                <div class="row">
                    <div class="col-12 mb-3">
                        <x-forms.label for="what">{{ __('What has been done') }}</x-forms.label>
                        <x-forms.textarea name="what" required></x-forms.textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <x-forms.label for="when">{{ __('When was this done') }}</x-forms.label>
                        <x-forms.textarea name="when" required></x-forms.textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <x-forms.label for="why">{{ __('Why was this done') }}</x-forms.label>
                        <x-forms.textarea name="why" required></x-forms.textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Create') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>
</x-layouts.ampp>
