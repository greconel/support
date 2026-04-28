@if(session($session))
    <div {{ $attributes->merge(['class' => 'toast text-white align-items-center']) }}
         role="alert" aria-live="assertive" aria-atomic="true" id="{{ $session }}Toast"
         x-ref="toast"
         x-data="{ mSeconds: 500, progress: 100, timer: null }"
         x-init="
            new bootstrap.Toast($refs.toast).show();
         "
    >
        <div class="d-flex">
            <div class="toast-body">
                {{ session($session) }}
            </div>

            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
@endif
