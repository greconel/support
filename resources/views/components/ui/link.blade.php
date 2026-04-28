<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => (request()->fullUrl() == $href) ? null : 'link-secondary']) }}
>{{ $slot }}</a>
