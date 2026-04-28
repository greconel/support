<div
    class="modal fade"
    id="invoicePdfPreviewModal"
    tabindex="-1"
    aria-labelledby="invoicePdfPreviewModalLabel"
    aria-hidden="true"
    wire:ignore.self
    x-ref="modal"
    x-data="{
        modal: new bootstrap.Modal($refs.modal),
        fullscreen: false
    }"
    x-init="
        $refs.modal.addEventListener('show.bs.modal', () => $wire.generate());
        $refs.modal.addEventListener('hidden.bs.modal', () => $refs.pdfViewer.remove());
    "
>
    <div class="modal-dialog" :class="fullscreen ? 'modal-fullscreen' : 'modal-xl'">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoicePdfPreviewModalLabel">{{ $invoice->file_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="w-100" wire:loading wire:target="generate">
                    <div class="d-flex align-items-center justify-content-center" style="height: 60rem">
                        <div class="spinner-grow" role="status" style="width: 4rem; height: 4rem">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>

                @if($pdf)
                    <div x-ref="pdfViewer" x-init="PDFObject.embed('data:application/pdf;base64,{{ $pdf }}', $refs.pdfViewer)" wire:ignore style="min-height: 100%; height: 60rem"></div>
                @endif
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>

                <div>
                    <button type="button" class="btn btn-info" @click="fullscreen = !fullscreen">
                        <i :class="fullscreen ? 'fas fa-compress-arrows-alt' : 'fas fa-expand-arrows-alt'"></i>
                    </button>

                    <button type="button" class="btn btn-primary" wire:click="download" wire:target="generate" wire:loading.class="disabled">
                        <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
