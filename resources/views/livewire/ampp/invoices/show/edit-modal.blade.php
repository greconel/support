<div
    class="modal fade"
    id="invoiceEditModal"
    aria-labelledby="invoiceEditModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    wire:ignore.self
    x-ref="modal"
    x-data="{
        modal: new bootstrap.Modal($refs.modal),
        clientId: $wire.entangle('invoice.client_id'),
        expirationDate: $wire.entangle('expirationDate'),
        customCreatedAt: $wire.entangle('customCreatedAt')
    }"
    x-init="
        new TomSelect($refs.select, {
            allowEmptyOption: true,
            hidePlaceholder: true
        });

        flatpickr($refs.datepickerExpirationDate, {
            altInput: true,
            altFormat: 'd/m/Y',
            defaultDate: expirationDate,
            dateFormat: 'Y-m-d',
            static: true
        });

        flatpickr($refs.datepickerCustomCreatedAt, {
            altInput: true,
            altFormat: 'd/m/Y',
            defaultDate: customCreatedAt,
            dateFormat: 'Y-m-d',
            static: true
        });

        $wire.on('close', () => modal.hide())
    "
>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceEditModalLabel">{{ __('Edit invoice') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form wire:submit.prevent="edit" id="invoiceEditForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-forms.label for="client_id">{{ __('Client') }}*</x-forms.label>

                            <div wire:ignore>
                                <select name="client_id" x-ref="select" x-model="clientId" class="form-control" required>
                                    @foreach($clients as $key => $value)
                                        <option value="{{ $key }}" @if($key == $invoice->client_id) selected @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <x-forms.error-message for="invoice.client_id" />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-forms.label for="expiration_date">{{ __('Expiration date') }}*</x-forms.label>

                            <div wire:ignore>
                                <input type="text" required class="form-control" x-ref="datepickerExpirationDate" x-model="expirationDate">
                            </div>

                            <x-forms.error-message for="expirationDate" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-forms.label for="number">{{ __('Number') }}*</x-forms.label>
                            <x-forms.input type="number" name="invoice.number" step="1" required wire:model="invoice.number" />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-forms.label for="expiration_date">{{ __('Created at') }}*</x-forms.label>

                            <div wire:ignore>
                                <input type="text" required class="form-control" x-ref="datepickerCustomCreatedAt" x-model="customCreatedAt">
                            </div>

                            <x-forms.error-message for="customCreatedAt" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <x-forms.label for="po_number">{{ __('PO Number') }}</x-forms.label>
                            <x-forms.input type="text" name="invoice.po_number" wire:model.defer="invoice.po_number" placeholder="{{ __('Purchase order reference...') }}" />
                            <x-forms.error-message for="invoice.po_number" />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-forms.label for="invoice_category_id">{{ __('Invoice category') }}</x-forms.label>
                            <select name="invoice_category_id" class="form-select" wire:model.defer="invoice.invoice_category_id">
                                <option value="">-</option>
                                @foreach($invoiceCategories as $catId => $catName)
                                    <option value="{{ $catId }}">{{ $catName }}</option>
                                @endforeach
                            </select>
                            <x-forms.error-message for="invoice.invoice_category_id" />
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>

                <button type="submit" form="invoiceEditForm" class="btn btn-primary">{{ __('Edit') }}</button>
            </div>
        </div>
    </div>
</div>
