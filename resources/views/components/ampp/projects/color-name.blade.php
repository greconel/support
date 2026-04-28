@props(['name', 'color'])

<div class="d-flex align-items-center gap-2">
    <span class="rounded-circle" style="width: 0.8rem; height: 0.8rem; background-color: {{ $color }}"></span>
    <span {{ $attributes }}>{{ $name }}</span>
</div>
