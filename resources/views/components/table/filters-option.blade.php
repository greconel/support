<a
    href="{{ $href() }}"

    @class([
        'dataTable-filters-option',
        'active' => $isActive()
    ])

    @if($description) data-bs-toggle="tooltip" data-bs-title="{{ $description }}" @endif
>
    <span>{{ $slot }}</span>
    <span class="counter">{{ $count }}</span>
</a>
