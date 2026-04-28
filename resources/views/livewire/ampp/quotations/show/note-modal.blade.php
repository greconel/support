<div
    class="modal fade"
    id="quotationNotesModal"
    aria-labelledby="quotationNotesModalLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    wire:ignore.self
    x-ref="modal"
    x-data="{ modal: new bootstrap.Modal($refs.modal) }"
    x-init="
        $refs.modal.addEventListener('show.bs.modal', () => $dispatch('refresh-quill'));
        $wire.on('close', () => modal.hide());
    "
>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quotationNotesModalLabel">{{ __('Edit notes') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form wire:submit.prevent="updateNotes" id="quotationNotesForm">
                    <x-forms.quill-livewire wire:model="quotation.notes" />
                    <x-forms.error-message for="quotation.notes" />
                </form>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="submit" class="btn btn-primary" form="quotationNotesForm">{{ __('Edit') }}</button>
            </div>
        </div>
    </div>
</div>
