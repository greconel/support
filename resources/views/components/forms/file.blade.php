@php
    $classes = 'form-control ' . ($errors->has($name) ? 'is-invalid' : null);
@endphp

<input
    type="file"
    name="{{ $name }}"
    {{ $attributes->except('model')->merge(['class' => $classes]) }}
>

@error($name)
<div class="invalid-feedback">
    {{ $message }}
</div>
@enderror
