<li
    @class([
        'nav-item',
        'ms-auto' => $alignEnd ?? false
    ])
>
    <a
        {{ $attributes->merge(['class' => 'nav-link']) }}
        href="{{ $href }}"
        type="button"
        role="tab"
    >
        {{ $slot }}
    </a>
</li>
