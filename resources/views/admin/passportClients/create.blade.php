<x-layouts.ampp :title="__('Create new passport client')" :breadcrumbs="Breadcrumbs::render('createPassportClient')">
    <div class="container">
        <x-ui.page-title>{{ __('Create new passport client') }}</x-ui.page-title>

        <div class="card">
            <div class="card-body">
                <x-forms.form
                    action="{{ action(\App\Http\Controllers\Admin\PassportClients\StorePassportClientController::class) }}"
                    method="post"
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
                            x-ref="users"
                        />
                    </div>

                    <div class="mb-3">
                        <x-forms.label for="name">{{ __('Passport client name') }}</x-forms.label>
                        <x-forms.input name="name" required/>
                    </div>

                    <div class="mb-3">
                        <x-forms.label for="redirect">{{ __('Redirect') }}</x-forms.label>
                        <x-forms.input name="redirect"/>
                    </div>

                    <div class="d-flex justify-content-end">
                        <x-forms.submit>{{ __('Create passport client') }}</x-forms.submit>
                    </div>
                </x-forms.form>
            </div>
        </div>
    </div>
</x-layouts.ampp>
