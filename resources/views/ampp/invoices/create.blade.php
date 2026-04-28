<x-layouts.ampp :title="__('Create new invoice')" :breadcrumbs="Breadcrumbs::render('createInvoice')">
    <div class="container">
        <x-ui.page-title>{{ __('Create new invoice') }}</x-ui.page-title>

        <div class="card card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Invoices\StoreInvoiceController::class) }}">
                <div class="row">
                    <div
                        class="col-md-6 mb-3"
                        x-data
                        x-init="new TomSelect($refs.clients, { allowEmptyOption: true, hidePlaceholder: true });"
                    >
                        <x-forms.label for="client_id">{{ __('Client') }}</x-forms.label>
                        <x-forms.select
                            name="client_id"
                            :options="$clients"
                            x-ref="clients"
                            required
                        />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="type">{{ __('Type of invoice') }}</x-forms.label>
                        <select name="type" class="form-select">
                            @foreach($types as $type)
                                <option value="{{ $type->value }}">{{ __($type->label()) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="custom_created_at">{{ __('Created at') }}</x-forms.label>
                        <x-forms.input name="custom_created_at" id="customCreatedAt" required />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="expiration_date">{{ __('Expiration date') }}</x-forms.label>
                        <x-forms.input name="expiration_date" required id="expirationDate" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="invoice_category_id">{{ __('Invoice category') }}</x-forms.label>
                        <x-forms.select name="invoice_category_id" id="invoiceCategoryId" :options="$invoiceCategories" />
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Create new invoice') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>

    <x-push name="scripts">
        <script>
            const expirationDate = flatpickr('#expirationDate', {
                minDate: new Date(),
                defaultDate: moment().add(30, 'days').toDate(),
                dateFormat: 'd/m/Y'
            });

            const customCreatedAt = flatpickr('#customCreatedAt', {
                defaultDate: new Date(),
                dateFormat: 'd/m/Y'
            });

            const clientCategoryMap = @json($clientCategoryMap);
            const clientSelect = document.querySelector('[name="client_id"]');
            const categorySelect = document.getElementById('invoiceCategoryId');

            if (clientSelect) {
                clientSelect.addEventListener('change', function() {
                    const categoryId = clientCategoryMap[this.value];
                    if (categoryId && categorySelect) {
                        categorySelect.value = categoryId;
                    }
                });
            }
        </script>
    </x-push>
</x-layouts.ampp>
