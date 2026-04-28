@php
    $classes = 'form-select ' . ($errors->has($errorFor) ? 'is-invalid' : null);
@endphp

<select
    name="{!! $name !!}"
    autocomplete="off"
    {{ $attributes->class($classes) }}
>
    @foreach($options as $value => $label)
        <option
            value="{{ $value }}"
            {{ in_array($value, $values) ? 'selected' : '' }}
        >
            {{ $label }}
        </option>
    @endforeach
</select>

@error($errorFor)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
@enderror
