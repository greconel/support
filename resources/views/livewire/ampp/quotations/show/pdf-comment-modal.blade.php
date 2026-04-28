<div
    class="modal fade"
    id="quotationPdfCommentModal"
    aria-labelledby="quotationPdfCommentModalLabel"
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
                <h5 class="modal-title" id="quotationPdfCommentModalLabel">{{ __('PDF comment') }}</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form wire:submit.prevent="updatePdfComment" id="quotationPdfCommentForm">
                    <x-forms.quill-livewire wire:model="quotation.pdf_comment" />
                    <x-forms.error-message for="quotation.pdf_comment" />
                </form>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>

                <button type="submit" class="btn btn-primary" form="quotationPdfCommentForm">{{ __('Edit') }}</button>
            </div>
        </div>
    </div>
</div>
