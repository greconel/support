@php
    $classes = 'form-check-input' . ($errors->has($errorFor) ? ' is-invalid' : '')
@endphp

<div class="form-check">
    <input
        name="{{ $name }}"
        type="checkbox"
        id="{{ $id ?? $name }}"
        @if($value)value="{{ $value }}"@endif
        {{ $checked ? 'checked' : '' }}
        {{ $attributes->merge(['class' => $classes]) }}
    />

    <label class="form-check-label" for="{{ $id ?? $name }}">
        {{ $slot }}
    </label>

    @error($errorFor)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
