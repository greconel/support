<table {{ $attributes->merge(['class' => 'table']) }}>
    @isset($headers)
        <thead>
            <tr>
                {{ $headers }}
            </tr>
        </thead>
    @endisset
    <tbody>
        {{ $rows }}
    </tbody>
</table>
