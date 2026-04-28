<div class="mb-4">
    <div class="d-flex justify-content-end mb-3">
        <a href="#quotationPdfCommentModal" class="link-primary" data-bs-toggle="modal">
            {{ __('Add/edit comment on PDF') }} <i class="far fa-sticky-note"></i>
        </a>
    </div>

    @if($quotation->pdf_comment)
        <x-ui.quill-display :content="$quotation->pdf_comment" />

        <hr>
    @endif
</div>
