<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bolder fs-4">{{ __('Notes') }}</span>

        <a href="#quotationNotesModal" class="link-secondary" data-bs-toggle="modal">
            <i class="far fa-edit"></i>
        </a>
    </div>

    <div class="card-body" style="max-height: 300px; overflow-y: auto">
        @if(! $quotation->notes)
            <div class="text-center text-muted">{{ __('No notes here..') }}</div>
        @else
            <x-ui.quill-display :content="$quotation->notes" />
        @endif
    </div>
</div>
