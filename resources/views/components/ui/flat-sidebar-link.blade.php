@props(['href', 'icon', 'count' => null])

@php
    $active = \Illuminate\Support\Str::startsWith(request()->url(), $href);
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->class(['fs-4 nav-link p-0', 'link-primary' => $active, 'link-gray-700' => ! $active]) }}
>
    <i class="{{ $icon }} text-gray-500" style="width: 45px;"></i>

    <span class="position-relative">
        {{ $slot }}

        @if($count)
            <span class="position-absolute top-0 translate-middle badge rounded-pill bg-primary small" style="right: -30px">
                {{ $count }}
            </span>
        @endif
    </span>
</a>
