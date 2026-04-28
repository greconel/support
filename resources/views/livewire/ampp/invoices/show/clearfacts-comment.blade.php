<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bolder fs-4">{{ __('Comment for Clearfacts') }}</span>

        <a href="#invoiceClearfactsCommentModal" class="link-secondary" data-bs-toggle="modal">
            <i class="far fa-edit"></i>
        </a>
    </div>

    <div class="card-body">
        @if(! $invoice->clearfacts_comment)
            <div class="text-center text-muted">{{ __('No comment here..') }}</div>
        @else
            {{ $invoice->clearfacts_comment }}
        @endif
    </div>
</div>

