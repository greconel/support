<li
    @class([
        'nav-item',
        'ms-auto' => $alignEnd ?? false
    ])
    role="presentation"
>
    <button
        {{ $attributes->merge(['class' => 'nav-link']) }}
        id="{{ $headerId }}"
        data-bs-toggle="tab"
        data-bs-target="#{{ $target }}"
        type="button"
        role="tab"
        aria-controls="{{ $headerId }}"
        aria-selected="true"
    >
        {{ $slot }}
    </button>
</li>
