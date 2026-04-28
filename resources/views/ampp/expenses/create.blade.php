<x-layouts.ampp :title="__('Create new expense')" :breadcrumbs="Breadcrumbs::render('createExpense')">
    <div class="container">
        <x-ui.page-title>{{ __('Create new expense') }}</x-ui.page-title>

        <div class="card card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Expenses\StoreExpenseController::class) }}" has-files>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="name">{{ __('Name (when empty, generated automatically)') }}</x-forms.label>
                        <x-forms.input name="name" />
                    </div>

                    <div
                        x-data
                        x-init="new TomSelect($refs.suppliers, { allowEmptyOption: false });"
                        class="col-md-6 mb-3"
                    >
                        <x-forms.label for="supplier_id">{{ __('Supplier') }}*</x-forms.label>
                        <x-forms.select
                            name="supplier_id"
                            :options="$suppliers"
                            x-ref="suppliers"
                            placeholder="{{ __('Select a supplier') }}"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div
                        x-data
                        x-init="
                            flatpickr($refs.input, {
                                defaultDate: new Date(),
                                altInput: true,
                                altFormat: 'd/m/Y',
                                dateFormat: 'Y-m-d'
                            });
                        "
                        class="col-md-4 mb-3"
                    >
                        <x-forms.label for="invoice_date">{{ __('Invoice date') }}*</x-forms.label>
                        <x-forms.input name="invoice_date" x-ref="input" required />
                    </div>

                    <div class="col-md-4 mb-3">
                        <x-forms.label for="invoice_number">{{ __('Invoice number') }}</x-forms.label>
                        <x-forms.input name="invoice_number" />
                    </div>

                    <div
                        x-data
                        x-init="Inputmask('+++999/9999/99999+++', { placeholder: '_' }).mask($refs.input);"
                        class="col-md-4 mb-3"
                    >
                        <x-forms.label for="invoice_ogm">{{ __('Invoice structured message (OGM)') }}</x-forms.label>
                        <x-forms.input name="invoice_ogm" x-ref="input" />
                    </div>
                </div>

                <div
                    x-data="{
                        amountExcludingVat: {{ json_encode(old('amount_excluding_vat', 0)) }},
                        amountVat: {{ json_encode(old('amount_vat', 0)) }},
                        amountVatPercentage: {{ json_encode(old('amount_vat_percentage', 0)) }},
                        vatAs: {{ json_encode(old('vat_as', 'percentage')) }},
                        get amountIncludingVat() {
                            if (this.vatAs === 'percentage'){
                                return (this.amountExcludingVat * (1 + this.amountVatPercentage / 100)).toFixed(2);
                            }

                            if (this.vatAs === 'euro'){
                                return (parseFloat(this.amountExcludingVat) + parseFloat(this.amountVat)).toFixed(2);
                            }

                            return 0;
                        }
                    }"

                    x-init="
                        $watch('amountIncludingVat', function(){
                            if (vatAs === 'percentage'){
                                amountVat = (parseFloat(amountIncludingVat) - parseFloat(amountExcludingVat)).toFixed(2);
                            }

                            if (vatAs === 'euro'){
                                amountVatPercentage = (((amountIncludingVat - amountExcludingVat) / amountExcludingVat) * 100).toFixed(2);
                            }
                        });
                    "

                    class="row"
                >
                    <div class="col-md-3 mb-3">
                        <x-forms.label for="amount_excluding_vat">{{ __('Amount excluding VAT') }}</x-forms.label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <x-forms.input type="number" step="0.01" name="amount_excluding_vat" x-model="amountExcludingVat" />
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <x-forms.label for="amount_including_vat">{{ __('Amount including VAT') }}</x-forms.label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <x-forms.input type="number" step="0.01" name="amount_including_vat" x-bind:value="amountIncludingVat" readonly />
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <x-forms.label for="amount_vat">{{ __('Amount of VAT (Euro)') }}</x-forms.label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input
                                    class="form-check-input mt-0"
                                    type="radio"
                                    name="vat_as"
                                    value="euro"
                                    aria-label="{{ __('VAT in Euro') }}"
                                    x-model="vatAs"
                                >
                            </div>

                            <span class="input-group-text">€</span>

                            <x-forms.input
                                type="number"
                                step="0.01"
                                name="amount_vat"
                                x-model="amountVat"
                                x-bind:readonly="vatAs === 'percentage'"
                            />
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <x-forms.label for="amount_vat_percentage">{{ __('Amount of VAT (percentage)') }}</x-forms.label>
                        <div class="input-group">
                            <div class="input-group-text">
                                <input
                                    class="form-check-input mt-0"
                                    type="radio"
                                    name="vat_as"
                                    value="percentage"
                                    aria-label="{{ __('VAT in percentage') }}"
                                    x-model="vatAs"
                                >
                            </div>

                            <x-forms.input
                                type="number"
                                step="0.01"
                                name="amount_vat_percentage"
                                x-model="amountVatPercentage"
                                x-bind:readonly="vatAs === 'euro'"
                            />

                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="various_transaction_category">{{ __('Various transaction category') }}</x-forms.label>
                        <x-forms.select
                            name="various_transaction_category"
                            :options="$variousTransactionCategories"
                            :values="\App\Enums\VariousTransactionCategory::Other->value"
                            required
                        />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="invoice_category_id">{{ __('Invoice category') }}</x-forms.label>
                        <x-forms.select name="invoice_category_id" id="invoiceCategoryId" :options="$invoiceCategories" />
                    </div>
                </div>

                <div class="mb-3">
                    <x-forms.label for="comment">{{ __('Comment') }}</x-forms.label>
                    <x-forms.quill name="comment" />
                </div>

                <div class="mb-3">
                    <div
                        x-data
                        x-init="
                            FilePond.create($refs.input, {
                                storeAsFile: true,
                                acceptedFileTypes: ['application/pdf'],
                                pdfComponentExtraParams: 'toolbar=0&view=FitV',
                                pdfPreviewHeight: 1000
                            });
                        "
                    >
                        <input type="file" name="file" x-ref="input" >
                    </div>

                    <x-forms.error-message for="file" />
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Create new expense') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>

    <x-push name="scripts">
        <script>
            const supplierCategoryMap = @json($supplierCategoryMap);
            const supplierSelect = document.querySelector('[name="supplier_id"]');
            const categorySelect = document.getElementById('invoiceCategoryId');

            if (supplierSelect) {
                supplierSelect.addEventListener('change', function() {
                    const categoryId = supplierCategoryMap[this.value];
                    if (categoryId && categorySelect) {
                        categorySelect.value = categoryId;
                    }
                });
            }
        </script>
    </x-push>
</x-layouts.ampp>
