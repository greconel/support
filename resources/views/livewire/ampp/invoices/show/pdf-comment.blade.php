<div class="mb-4">
    <div class="d-flex justify-content-end mb-3">
        <a href="#invoicePdfCommentModal" class="link-primary" data-bs-toggle="modal">
            {{ __('Add PDF comment') }} <i class="far fa-sticky-note"></i>
        </a>
    </div>

    @if($invoice->pdf_comment)
        <x-ui.quill-display :content="$invoice->pdf_comment" />

        <hr>
    @endif
</div>
