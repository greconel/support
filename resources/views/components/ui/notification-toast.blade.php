@if(session($notification))
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="{{ $notification }}Toast">
        <div class="toast-header">
            <img src="{{ config('ampp.logos.main_black') }}" class="rounded me-2 img-fluid" style="height: 15px" alt="ampp">
            <strong class="me-auto">{{ config('app.name') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>

        <div class="toast-body">
            {{ session($notification) }}
        </div>
    </div>

    <x-push name="scripts">
        <script>
            new bootstrap.Toast(document.getElementById("{{ $notification }}Toast")).show();
        </script>
    </x-push>
@endif
