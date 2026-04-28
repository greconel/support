@php
    $classes = 'form-control' . ($errors->has($errorFor) ? ' is-invalid' : '')
@endphp

<input
    name="{{ $name }}"
    type="{{ $type }}"
    @if($id) id="{{ $id }}" @endif
    @if($value) value="{{ $value }}" @endif
    {{ $attributes->merge(['class' => $classes]) }}
/>

@error($errorFor)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror
