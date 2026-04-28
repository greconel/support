<x-layouts.ampp :title="__('Create new client contact')" :breadcrumbs="Breadcrumbs::render('createClientContact', $client)">
    <div class="container">
        <x-ui.page-title>{{ __('Create new client contact') }}</x-ui.page-title>

        <div class="card card-body">
            <x-ui.session-alert session="address_error" class="alert-danger" />

            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\ClientContacts\StoreClientContactController::class, $client) }}" method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="first_name">{{ __('First name') }}</x-forms.label>
                        <x-forms.input name="first_name" required />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="last_name">{{ __('Last name') }}*</x-forms.label>
                        <x-forms.input name="last_name" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="email">{{ __('Email') }}</x-forms.label>
                        <x-forms.input type="email" name="email" />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="phone">{{ __('Phone') }}</x-forms.label>
                        <x-forms.input name="phone" />
                    </div>
                </div>

                <div
                    class="mb-3"
                    x-data
                    x-init="
                            new TomSelect($refs.tags, { allowEmptyOption: true, hidePlaceholder: true, create: true });
                        "
                >
                    <x-forms.label for="tags[]">{{ __('Tags') }}</x-forms.label>
                    <x-forms.select
                        name="tags[]"
                        multiple
                        x-ref="tags"
                    />
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <x-forms.label for="description">{{ __('Description') }}</x-forms.label>
                        <x-forms.quill name="description" />
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Create new contact') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>
</x-layouts.ampp>
