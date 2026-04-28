<x-layouts.ampp :title="__('Edit GDPR audit')" :breadcrumbs="Breadcrumbs::render('editGdprAudit', $audit)">
    <div class="container">
        <x-ui.page-title>{{ __('Edit GDPR audit') }}</x-ui.page-title>

        <div class="card card-body">
            <x-ui.session-alert session="address_error" class="alert-danger" />

            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\GdprAudits\UpdateGdprAuditController::class, $audit) }}" method="patch">
                <div class="row">
                    <div class="col-12 mb-3">
                        <x-forms.label for="what">{{ __('What has been done') }}</x-forms.label>
                        <x-forms.textarea name="what" required>{{ $audit->what }}</x-forms.textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <x-forms.label for="when">{{ __('When was this done') }}</x-forms.label>
                        <x-forms.textarea name="when" required>{{ $audit->when }}</x-forms.textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <x-forms.label for="why">{{ __('Why was this done') }}</x-forms.label>
                        <x-forms.textarea name="why" required>{{ $audit->why }}</x-forms.textarea>
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
                {{ __('Are you sure you want to delete this GDPR audit? This action can not be reverted!') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form :action="action(\App\Http\Controllers\Ampp\GdprAudits\DestroyGdprAuditController::class, $audit)" method="delete">
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
