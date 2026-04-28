@props([
    'icon',
    'title',
    'text',
    'maxHeight' => '100px'
])

<div class="card card-body" style="max-height: {{ $maxHeight }}; overflow-y: auto">
    <div class="d-flex gap-3">
        {{ $icon }}

        <div class="d-flex flex-column">
            <span class="text-gray-500">{{ $title }}</span>

            <div>
                {{ $text }}
            </div>
        </div>
    </div>
</div>
