<x-layouts.ampp :title="__('Create new supplier')" :breadcrumbs="Breadcrumbs::render('createSupplier')">
    <div class="container">
        <x-ui.page-title>{{ __('Create new supplier') }}</x-ui.page-title>

        <div class="card card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Suppliers\StoreSupplierController::class) }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="first_name">{{ __('First name') }}</x-forms.label>
                        <x-forms.input name="first_name" />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="last_name">{{ __('Last name') }}</x-forms.label>
                        <x-forms.input name="last_name" />
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <x-forms.label for="company">{{ __('Company') }}*</x-forms.label>
                        <x-forms.input name="company" required />
                    </div>

                    <div class="col-md-4 mb-3">
                        <livewire:ampp.suppliers.vat :vat="old('vat')" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <x-forms.label for="iban">{{ __('Iban') }}</x-forms.label>
                        <x-forms.input name="iban"/>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="email">{{ __('Email') }}</x-forms.label>
                        <x-forms.input type="email" name="email"/>
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="phone">{{ __('Phone') }}</x-forms.label>
                        <x-forms.input name="phone"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="street">{{ __('Street') }}</x-forms.label>
                        <x-forms.input name="street"/>
                    </div>

                    <div class="col-md-2 mb-3">
                        <x-forms.label for="number">{{ __('Number') }}</x-forms.label>
                        <x-forms.input name="number"/>
                    </div>

                    <div class="col-md-4 mb-3">
                        <x-forms.label for="postal">{{ __('Postal') }}</x-forms.label>
                        <x-forms.input name="postal"/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="city">{{ __('City') }}</x-forms.label>
                        <x-forms.input name="city"/>
                    </div>

                    <div
                        class="col-md-6 mb-3"
                        x-data
                        x-init="
                            new TomSelect($refs.countries, { allowEmptyOption: true, hidePlaceholder: true });
                        "
                    >
                        <x-forms.label for="country">{{ __('Country') }}</x-forms.label>
                        <x-forms.select name="country" x-ref="countries" :options="$countries" :values="['Belgium']" />
                    </div>
                </div>

                <div class="mb-4">
                    <x-forms.label for="notes">{{ __('Notes') }}</x-forms.label>
                    <x-forms.quill name="notes" />
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="invoice_category_id">{{ __('Default invoice category') }}</x-forms.label>
                        <x-forms.select name="invoice_category_id" :options="$invoiceCategories" />
                    </div>
                </div>

                <div class="mb-3">
                    <x-forms.checkbox name="is_general" value="1">
                        {{ __('This is a general supplier (expenses from this supplier will not be included in some calculations)') }}
                    </x-forms.checkbox>
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Create new supplier') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>
</x-layouts.ampp>
