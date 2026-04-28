<form action="{{ $action }}" method="{{ $method !== 'GET' ? 'POST' : 'GET' }}" {!! $hasFiles ? 'enctype="multipart/form-data"' : '' !!} {{ $attributes }}>
    @csrf
    @method($method)

    {{ $slot }}
</form>
