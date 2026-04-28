<x-layouts.ampp :title="__('Edit recurring invoice')" :breadcrumbs="Breadcrumbs::render('editRecurringInvoice', $recurringInvoice)">
    <div class="container">
        <x-ui.page-title>{{ __('Edit recurring invoice') }}</x-ui.page-title>

        <div class="card card-body">
            <x-forms.form action="{{ action(\App\Http\Controllers\Ampp\RecurringInvoices\UpdateRecurringInvoiceController::class, $recurringInvoice) }}" method="PATCH">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="name">{{ __('Name') }}</x-forms.label>
                        <x-forms.input name="name" :value="$recurringInvoice->name" required />
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
                            :values="$recurringInvoice->client_id"
                            x-ref="clients"
                            required
                        />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <x-forms.label for="period">{{ __('Period') }}</x-forms.label>
                        <select name="period" class="form-select" required>
                            @foreach($periods as $period)
                                <option value="{{ $period->value }}" {{ $recurringInvoice->period == $period ? 'selected' : '' }}>{{ $period->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <x-forms.label for="notes">{{ __('Notes') }}</x-forms.label>
                        <textarea name="notes" class="form-control" rows="2">{{ $recurringInvoice->notes }}</textarea>
                    </div>

                    <div class="col-md-4 mb-3 d-flex align-items-center">
                        <div class="form-check mt-4">
                            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ $recurringInvoice->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">{{ __('Active') }}</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <x-forms.label for="po_number">{{ __('PO Number') }}</x-forms.label>
                        <x-forms.input name="po_number" :value="$recurringInvoice->po_number" placeholder="{{ __('Purchase order reference...') }}" />
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <x-forms.submit>{{ __('Save changes') }}</x-forms.submit>
                </div>
            </x-forms.form>
        </div>
    </div>
</x-layouts.ampp>
