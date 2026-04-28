@php
    $active = \Illuminate\Support\Str::startsWith(request()->url(), $href);
@endphp

<a
    class="list-group-item list-group-item-action {{ $active ? 'active' : null }}"
    href="{{ $href }}"
    role="tab"
    aria-selected="{{ $active }}"
>
    {{ $slot }}
</a>
