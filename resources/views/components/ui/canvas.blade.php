<div>
    <div class="offcanvas offcanvas-end" id="{{ $id }}" aria-labelledby="{{ $id }}Label" {{ $attributes }}>
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-muted" id="{{ $id }}Label">{!! $title ?? null !!}</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            {{ $slot }}
        </div>
    </div>
</div>
