<button id="{{ $id }}" {{ $attributes->merge(['class' => 'btn']) }}>
    {{ $slot }}
</button>

<x-push name="scripts">
    <script>
        const {{ $id }} = new bootstrap.Popover(document.getElementById('{{ $id }}'), {
            trigger: 'focus',
            container: 'body',
            html: true,
            sanitize: false,
            title: '{{ $title }}',
            content: `{{ $content }}`,
        });
    </script>
</x-push>

