@props([
    'id',
    'icon',
    'text',
    'show' => true,
    'parent' => null,
    'iconColor' => null
])

@if($show)
    <li class="sidebar-item">
        <a data-bs-target="#{{ $id }}"
           data-bs-toggle="collapse"
           class="sidebar-link collapsed"
        >
            @isset($icon)
                <i
                    class="{{ $icon }} align-middle"
                    @if($iconColor) style="color: {{ $iconColor }}" @endif
                ></i>
            @endisset

            <span class="align-middle">{{ $text }}</span>
        </a>

        <ul id="{{ $id }}"
            data-bs-parent="#{{ $parent ?? 'sidebar' }}"
            class="sidebar-dropdown list-unstyled collapse"
        >
            <div class="sidebar-dropdown-wrapper">
                {{ $slot }}
            </div>
        </ul>
    </li>
@endif
