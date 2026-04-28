<x-layouts.ampp :title="__('Edit user')" :breadcrumbs="Breadcrumbs::render('editUser', $user)">
    <div class="container">
        <x-ui.page-title>{{ __('Edit user :name', ['name' => $user->name]) }}</x-ui.page-title>

        <div class="card">
            <div class="card-body">
                <x-forms.form action="{{ action(\App\Http\Controllers\Admin\Users\UpdateUserController::class, $user) }}" method="patch">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                            <x-forms.input name="name" :value="$user->name" required />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-forms.label for="email">{{ __('Email') }}</x-forms.label>
                            <x-forms.input type="email" name="email" :value="$user->email" required />
                        </div>
                    </div>

                    <div class="row">
                        <div
                            class="col-md-6 mb-3"
                            x-data
                            x-init="
                                new TomSelect($refs.roles, { allowEmptyOption: true, hidePlaceholder: true });
                            "
                        >
                            <x-forms.label for="roles[]">{{ __('Roles') }}</x-forms.label>
                            <x-forms.select
                                name="roles[]"
                                :options="$roles"
                                :values="$user->roles->pluck('id')->toArray()"
                                multiple
                                x-ref="roles"
                            />
                        </div>
                        <div class="col-md-6 mb-3">
                            <x-forms.label for="motion_user_id">{{ __('Motion user ID') }}</x-forms.label>
                            <x-forms.input name="motion_user_id" :value="$user->motion_user_id"/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col mb-3">
                            <x-forms.checkbox name="password_resend">{{ __('Create and send a new password') }}</x-forms.checkbox>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <x-forms.submit>{{ __('Edit user') }}</x-forms.submit>
                    </div>
                </x-forms.form>
            </div>
        </div>
    </div>
</x-layouts.ampp>
