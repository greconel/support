<x-layouts.ampp :title="__('Create new project')" :breadcrumbs="Breadcrumbs::render('createProject')">
    <div class="container">
        <x-ui.page-title>{{ __('Create new project') }}</x-ui.page-title>

        <div class="card card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Projects\StoreProjectController::class) }}" method="post">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <x-forms.label for="name">{{ __('Name') }}*</x-forms.label>
                        <x-forms.input name="name" required />
                    </div>

                    <div
                        class="col-md-6 mb-3"
                        x-data
                        x-init="new TomSelect($refs.clients, { allowEmptyOption: true });"
                    >
                        <x-forms.label for="client_id">{{ __('Client') }}</x-forms.label>
                        <x-forms.select
                            name="client_id"
                            :options="$clients"
                            x-ref="clients"
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <x-forms.label for="budget_money">{{ __('Budget in euro') }}</x-forms.label>

                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <x-forms.input type="number" name="budget_money" step=".01" />
                        </div>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <x-forms.label for="budget_hours">{{ __('Budget in hours') }}</x-forms.label>
                        <x-forms.input type="number" name="budget_hours" step="1" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="category">{{ __('Category') }}</x-forms.label>

                        <x-forms.select name="category" :options="\App\Enums\ProjectCategory::ForSelect()" />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="color">{{ __('Color') }}</x-forms.label>
                        <div
                            x-data="{ color: '#000000ff' }"
                            x-init="
                                picker = new Picker({
                                    parent: $refs.button,
                                    color,
                                    onDone: rawColor => color = rawColor.hex,
                                    popup: 'bottom',
                                });
                            "
                        >
                            <input type="hidden" name="color" x-model="color">
                            <button class="btn w-100" x-text="color" :style="`background: ${color} !important`" x-ref="button"></button>
                        </div>
                        <x-forms.error-message for="color" />
                    </div>
                </div>

                <div class="mb-3">
                    <x-forms.label for="users[]">{{ __('Users') }}</x-forms.label>
                    <x-forms.user-select
                        name="users[]"
                        id="users"
                        :users="$users"
                        :values="auth()->id()"
                        multiple
                        required
                    />
                </div>

                <div class="mb-4">
                    <x-forms.label for="description">{{ __('Description') }}</x-forms.label>
                    <x-forms.quill name="description" />
                    <x-forms.error-message for="description" />
                </div>

                <div class="mb-3">
                    <x-forms.checkbox name="is_general" value="1">
                        {{ __('This is a general project (time registrations for this project will act as if they belong to no project)') }}
                    </x-forms.checkbox>
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Create new project') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>
</x-layouts.ampp>

