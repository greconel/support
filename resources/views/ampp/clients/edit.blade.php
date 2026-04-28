<x-layouts.ampp :title="__('Edit client')" :breadcrumbs="Breadcrumbs::render('editClient', $client)">
    <div class="container">
        <x-ui.page-title>{{ __('Edit client :name', ['name' => $client->full_name]) }}</x-ui.page-title>

        <div class="card card-body">
            <x-ui.session-alert session="address_error" class="alert-danger"/>

            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Clients\UpdateClientController::class, $client) }}" method="patch">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="type">{{ __('Type') }}</x-forms.label>
                        <x-forms.select name="type" :options="$types" :values="$client->type->value" required />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="first_name">{{ __('First name') }}*</x-forms.label>
                        <x-forms.input name="first_name" :value="$client->first_name" required/>
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="last_name">{{ __('Last name') }}*</x-forms.label>
                        <x-forms.input name="last_name" :value="$client->last_name" required/>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="company">{{ __('Company') }}</x-forms.label>
                        <x-forms.input name="company" :value="$client->company"/>
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="vat">{{ __('Vat') }}</x-forms.label>
                        <x-forms.input name="vat" :value="$client->vat"/>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="email">{{ __('Email') }}</x-forms.label>
                        <x-forms.input type="email" name="email" :value="$client->email"/>
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="phone">{{ __('Phone') }}</x-forms.label>
                        <x-forms.input name="phone" :value="$client->phone"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="street">{{ __('Street') }}</x-forms.label>
                        <x-forms.input name="street" :value="$client->street"/>
                    </div>

                    <div class="col-md-2 mb-3">
                        <x-forms.label for="number">{{ __('Number') }}</x-forms.label>
                        <x-forms.input name="number" :value="$client->number"/>
                    </div>

                    <div class="col-md-4 mb-3">
                        <x-forms.label for="postal">{{ __('Postal') }}</x-forms.label>
                        <x-forms.input name="postal" :value="$client->postal"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="city">{{ __('City') }}</x-forms.label>
                        <x-forms.input name="city" :value="$client->city"/>
                    </div>

                    <div
                        class="col-md-6 mb-3"
                        x-data
                        x-init="
                            new TomSelect($refs.countries, { allowEmptyOption: true, hidePlaceholder: true });
                        "
                    >
                        <x-forms.label for="country">{{ __('Country') }}</x-forms.label>
                        <x-forms.select name="country" x-ref="countries" :options="$countries" :values="$client->country"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="lat">{{ __('Lat') }}</x-forms.label>
                        <x-forms.input name="lat" :value="$client->lat" disabled/>
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="lng">{{ __('Lng') }}</x-forms.label>
                        <x-forms.input name="lng" :value="$client->lng" disabled/>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12 mb-3">
                        <x-forms.checkbox name="get_coordinates" value="1">{{ __('Search for coordinates') }}</x-forms.checkbox>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="invoice_note">{{ __('Invoice note') }}</x-forms.label>
                        <x-forms.input name="invoice_note" :value="$client->invoice_note"/>
                    </div>

                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <x-forms.checkbox name="peppol_only" value="1" :checked="$client->peppol_only">
                            {{ __('Invoices only via Peppol') }}
                        </x-forms.checkbox>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="invoice_category_id">{{ __('Default invoice category') }}</x-forms.label>
                        <x-forms.select name="invoice_category_id" :options="$invoiceCategories" :values="$client->invoice_category_id" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="motion_project_id">{{ __('Motion project ID') }}</x-forms.label>
                        <x-forms.input name="motion_project_id" :value="$client->motion_project_id"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <x-forms.label for="description">{{ __('Description') }}</x-forms.label>
                        <x-forms.quill name="description" :value="$client->description" />
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Edit') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>
</x-layouts.ampp>
