<label
    for="{{ $for ?? null }}"
    {{ $attributes->merge(['class' => 'form-label']) }}
>
    {{ $slot }}
</label>
