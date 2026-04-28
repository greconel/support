<x-layouts.ampp :title="__('Create recurring invoice')" :breadcrumbs="Breadcrumbs::render('createRecurringInvoice')">
    <div class="container">
        <x-ui.page-title>{{ __('Create recurring invoice') }}</x-ui.page-title>

        <div class="card card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\RecurringInvoices\StoreRecurringInvoiceController::class) }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                        <x-forms.input name="name" required />
                    </div>

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
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="period">{{ __('Period') }}</x-forms.label>
                        <select name="period" class="form-select" required>
                            @foreach($periods as $period)
                                <option value="{{ $period->value }}">{{ $period->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-forms.label for="notes">{{ __('Notes') }}</x-forms.label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="po_number">{{ __('PO Number') }}</x-forms.label>
                        <x-forms.input name="po_number" placeholder="{{ __('Purchase order reference...') }}" />
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Create recurring invoice') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>
</x-layouts.ampp>
