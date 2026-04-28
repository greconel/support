<x-layouts.ampp :title="__('Edit supplier')" :breadcrumbs="Breadcrumbs::render('editSupplier', $supplier)">
    <div class="container">
        <x-ui.page-title>{{ __('Edit supplier') }}</x-ui.page-title>

        <div class="card card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Suppliers\UpdateSupplierController::class, $supplier) }}" method="patch">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="first_name">{{ __('First name') }}</x-forms.label>
                        <x-forms.input name="first_name" :value="$supplier->first_name" />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="last_name">{{ __('Last name') }}</x-forms.label>
                        <x-forms.input name="last_name" :value="$supplier->last_name" />
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <x-forms.label for="company">{{ __('Company') }}*</x-forms.label>
                        <x-forms.input name="company" :value="$supplier->company" required />
                    </div>

                    <div class="col-md-4 mb-3">
                        <livewire:ampp.suppliers.vat :vat="old('vat', $supplier->vat)" :supplier="$supplier" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <x-forms.label for="iban">{{ __('Iban') }}</x-forms.label>
                        <x-forms.input name="iban" :value="$supplier->iban" />
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="email">{{ __('Email') }}</x-forms.label>
                        <x-forms.input type="email" name="email" :value="$supplier->email" />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="phone">{{ __('Phone') }}</x-forms.label>
                        <x-forms.input name="phone" :value="$supplier->phone" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="street">{{ __('Street') }}</x-forms.label>
                        <x-forms.input name="street" :value="$supplier->street" />
                    </div>

                    <div class="col-md-2 mb-3">
                        <x-forms.label for="number">{{ __('Number') }}</x-forms.label>
                        <x-forms.input name="number" :value="$supplier->number" />
                    </div>

                    <div class="col-md-4 mb-3">
                        <x-forms.label for="postal">{{ __('Postal') }}</x-forms.label>
                        <x-forms.input name="postal" :value="$supplier->postal" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="city">{{ __('City') }}</x-forms.label>
                        <x-forms.input name="city" :value="$supplier->city" />
                    </div>

                    <div
                        class="col-md-6 mb-3"
                        x-data
                        x-init="
                            new TomSelect($refs.countries, { allowEmptyOption: true, hidePlaceholder: true });
                        "
                    >
                        <x-forms.label for="country">{{ __('Country') }}</x-forms.label>
                        <x-forms.select name="country" x-ref="countries" :options="$countries" :values="$supplier->country" />
                    </div>
                </div>

                <div class="mb-4">
                    <x-forms.label for="notes">{{ __('Notes') }}</x-forms.label>
                    <x-forms.quill name="notes" :value="$supplier->notes" />
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="invoice_category_id">{{ __('Default invoice category') }}</x-forms.label>
                        <x-forms.select name="invoice_category_id" :options="$invoiceCategories" :values="$supplier->invoice_category_id" />
                    </div>
                </div>

                <div class="mb-3">
                    <x-forms.checkbox name="is_general" value="1" :checked="$supplier->is_general">
                        {{ __('This is a general supplier (expenses from this supplier will not be included in some calculations)') }}
                    </x-forms.checkbox>
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Edit supplier') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>
</x-layouts.ampp>
