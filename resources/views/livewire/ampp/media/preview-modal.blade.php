<div
    class="modal fade"
    id="mediaPreviewModal"
    tabindex="-1"
    aria-labelledby="mediaPreviewModalLabel"
    aria-hidden="true"
    wire:ignore.self
    x-ref="modal"
    x-data="{
        modal: new bootstrap.Modal($refs.modal),
        fullscreen: false
    }"
    x-init="
        $wire.on('toggle', () => modal.toggle());
    "
>
    <div class="modal-dialog" :class="fullscreen ? 'modal-fullscreen' : 'modal-xl'">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaPreviewModalLabel">{{ $media?->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                @if(str_starts_with($media?->mime_type, 'image'))
                    <img src="{{ action(\App\Http\Controllers\Media\ShowMediaController::class, $media) }}" alt="{{ $media?->name }}" class="img-fluid rounded d-block mx-auto">
                @elseif(str_contains($media?->mime_type, 'pdf'))
                    <div x-ref="pdfViewer" x-init="PDFObject.embed('{{ action(\App\Http\Controllers\Media\ShowMediaController::class, $media) }}', $refs.pdfViewer)" wire:ignore style="min-height: 100%; height: 60rem"></div>
                @endif
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>

                <div>
                    <button type="button" class="btn btn-info" @click="fullscreen = !fullscreen">
                        <i :class="fullscreen ? 'fas fa-compress-arrows-alt' : 'fas fa-expand-arrows-alt'"></i>
                    </button>

                    <button type="button" class="btn btn-primary" wire:click="download"><i class="fas fa-download"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
