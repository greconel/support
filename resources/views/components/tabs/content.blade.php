<div
    {{ $attributes->merge(['class' => 'tab-pane fade']) }}
    id="{{ $id }}"
    role="tabpanel"
    aria-labelledby="{{ $id }}-tab"
>
    {{ $slot }}
</div>
