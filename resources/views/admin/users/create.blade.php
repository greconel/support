<x-layouts.ampp :title="__('Create new user')" :breadcrumbs="Breadcrumbs::render('createUser')">
    <div class="container">
        <x-ui.page-title>{{ __('Create new user') }}</x-ui.page-title>

        <div class="card">
            <div class="card-body">
                <x-forms.form action="{{ action(\App\Http\Controllers\Admin\Users\StoreUserController::class) }}" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                            <x-forms.input name="name" required />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-forms.label for="email">{{ __('Email') }}</x-forms.label>
                            <x-forms.input type="email" name="email" required />
                        </div>
                    </div>

                    <div
                        class="mb-3"
                        x-data
                        x-init="
                            new TomSelect($refs.roles, { allowEmptyOption: true, hidePlaceholder: true });
                        "
                    >
                        <x-forms.label for="roles[]">{{ __('Roles') }}</x-forms.label>
                        <x-forms.select
                            name="roles[]"
                            :options="$roles"
                            multiple
                            x-ref="roles"
                        />
                    </div>

                    <div class="d-flex justify-content-end">
                        <x-forms.submit>{{ __('Create user') }}</x-forms.submit>
                    </div>
                </x-forms.form>

                <p class="small p-0 m-0">{{ __('*A random password will be sent to the user') }}</p>
            </div>
        </div>
    </div>
</x-layouts.ampp>
