<div
    class="modal fade"
    id="invoicePaymentModal"
    aria-labelledby="invoicePaymentModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    wire:ignore.self
    x-ref="modal"
    x-data="{ modal: new bootstrap.Modal($refs.modal) }"
    x-init="
        $refs.modal.addEventListener('hidden.bs.modal', () => $wire.resetForm());
        $wire.on('close', () => modal.hide());
    "
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoicePaymentModalLabel">
                    {{ $paymentId ? __('Edit payment') : __('Add payment') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form wire:submit.prevent="save" id="invoicePaymentForm">
                    <div class="mb-3">
                        <x-forms.label for="amount" required>{{ __('Amount') }}</x-forms.label>
                        <div class="input-group">
                            <span class="input-group-text">&euro;</span>
                            <x-forms.input
                                name="amount"
                                type="number"
                                step="0.01"
                                min="0.01"
                                wire:model.defer="amount"
                                placeholder="0.00"
                            />
                        </div>
                        <small class="text-muted">
                            {{ __('Maximum') }}: {{ $invoice->remaining_balance_formatted }}
                        </small>
                    </div>

                    <div class="mb-3">
                        <x-forms.label for="paidAt" required>{{ __('Payment date') }}</x-forms.label>
                        <x-forms.input name="paidAt" type="date" wire:model.defer="paidAt" />
                    </div>

                    <div class="mb-3">
                        <x-forms.label for="paymentMethod">{{ __('Payment method') }}</x-forms.label>
                        <select wire:model.defer="paymentMethod" class="form-select" name="paymentMethod">
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->value }}">{{ $method->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <x-forms.label for="notes">{{ __('Notes') }}</x-forms.label>
                        <x-forms.textarea name="notes" wire:model.defer="notes" rows="2" />
                    </div>
                </form>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="submit" class="btn btn-primary" form="invoicePaymentForm">
                    {{ $paymentId ? __('Update') : __('Save') }}
                </button>
            </div>
        </div>
    </div>
</div>
