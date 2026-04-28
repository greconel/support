<li>
    <button
        {{ $attributes->merge(['class' => 'dropdown-item']) }}
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
