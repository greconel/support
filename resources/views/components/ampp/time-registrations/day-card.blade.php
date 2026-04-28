@props([
    'day',
    'time',
    'active' => false,
    'total' => false,
    'runningTimeRegistration' => false
])

<div
    @class(['p-3 d-flex flex-column', 'active' => $active, 'total' => $total])
    {{ $attributes->wire('click') }}
>
    <span @class(['fw-bolder' => $total])>
        {{ $day }}

        @if($runningTimeRegistration)
            <i class="fas fa-exclamation-circle text-warning"></i>
        @endif
    </span>
    <span>{{ $time }}</span>
</div>
