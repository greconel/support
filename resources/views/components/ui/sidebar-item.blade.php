@props([
    'href',
    'show' => true,
    'extraActiveUrls' => []
])

@if($show)
    @php
        $active = false;

        $activeUrls = [...$extraActiveUrls, $href];

        foreach ($activeUrls as $url){
            if (\Illuminate\Support\Str::startsWith(request()->url(), $url)){
                $active = true;
                break;
            }
        }
    @endphp

    <li @class(['sidebar-item', 'active' => $active])>
        <a href="{{ $href }}" {{ $attributes->merge(['class' => 'sidebar-link']) }}>
            {{ $slot }}
        </a>
    </li>
@endif
