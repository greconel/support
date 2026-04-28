<x-layouts.ampp :title="__('Create new quotation')" :breadcrumbs="Breadcrumbs::render('createQuotation')">
    <div class="container">
        <x-ui.page-title>{{ __('Create new quotation') }}</x-ui.page-title>

        <div class="card card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\Quotations\StoreQuotationController::class) }}">
                <input type="hidden" name="deal_id" value="{{ request()->input('deal') }}">

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
                            :values="request()->input('client')"
                            x-ref="clients"
                            required
                        />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="expiration_date">{{ __('Expiration date') }}*</x-forms.label>
                        <x-forms.input name="expiration_date" required id="expirationDate" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="number">{{ __('Number') }}*</x-forms.label>
                        <x-forms.input type="number" name="number" :value="$number" step="1" required />
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="custom_created_at">{{ __('Created at') }}*</x-forms.label>
                        <x-forms.input name="custom_created_at" id="customCreatedAt" required />
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Create new quotation') }}</x-forms.submit>
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
        </script>
    </x-push>
</x-layouts.ampp>
