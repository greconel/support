<x-layouts.ampp :title="__('Edit passport client')" :breadcrumbs="Breadcrumbs::render('editPassportClient', $passportClient)">
    <div class="container">
        <div class="card card-body">
            <x-forms.form
                action="{{ action(\App\Http\Controllers\Admin\PassportClients\UpdatePassportClientController::class, $passportClient) }}"
                method="patch"
            >

                <div
                    class="mb-3"
                    x-data
                    x-init="
                        new TomSelect($refs.users, { allowEmptyOption: true, hidePlaceholder: true });
                    "
                >
                    <x-forms.label for="user_id">{{ __('User') }}</x-forms.label>
                    <x-forms.select
                        name="user_id"
                        :options="$users"
                        :values="$passportClient->user?->id"
                        x-ref="users"
                    />
                </div>

                <div class="mb-3">
                    <x-forms.label for="name">{{ __('Passport client name') }}</x-forms.label>
                    <x-forms.input name="name" :value="$passportClient->name" required/>
                </div>

                <div class="mb-3">
                    <x-forms.label for="redirect">{{ __('Redirect') }}</x-forms.label>
                    <x-forms.input name="redirect" :value="$passportClient->redirect"/>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="#confirmDeleteModal" data-bs-toggle="modal" class="link-secondary">{{ __('Delete passport client') }}</a>

                    <x-forms.submit>{{ __('Edit passport client') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>

    <x-push name="modals">
        <x-ui.confirmation-modal id="confirmDeleteModal">
            <x-slot name="content">
                {{ __('Are you sure you want to delete this passport client?') }}
            </x-slot>

            <x-slot name="actions">
                <x-forms.form
                    :action="action(\App\Http\Controllers\Admin\PassportClients\DestroyPassportClientController::class, $passportClient)"
                    method="delete"
                >
                    <button class="btn btn-danger">{{ __('Delete') }}</button>
                </x-forms.form>
            </x-slot>
        </x-ui.confirmation-modal>
    </x-push>
</x-layouts.ampp>
