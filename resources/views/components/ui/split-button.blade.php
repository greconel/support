@props([
    'button',
    'dropdown',
])

<div class="btn-group">
    {{ $button }}

    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="visually-hidden">Toggle Dropdown</span>
    </button>

    <ul class="dropdown-menu dropdown-menu-end">
        {{ $dropdown }}
    </ul>
</div>
