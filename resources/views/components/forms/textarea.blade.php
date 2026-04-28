@php
    $classes = 'form-control ' . ($errors->has($name) ? 'is-invalid' : null);
@endphp

<textarea
    name="{{ $name }}"
    @if($id) id="{{ $id }}" @endif
    rows="{{ $rows }}"
    {{ $attributes->merge(['class' => $classes]) }}
>{{ old($name, $slot) }}</textarea>

@error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror
