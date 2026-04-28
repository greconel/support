<button class="btn btn-danger" id="{{ $id }}" {{ $attributes }}>
    {{ __('Delete') }}
</button>

<x-push name="scripts">
    <script>
        const {{ $id }} = new bootstrap.Popover( document.getElementById('{{ $id }}'), {
            trigger: 'click',
            container: 'body',
            html: true,
            sanitize: false,
            title: '{{ __('general.delete_confirm') }}',
            content: `
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-sm btn-danger" id="confirm_{{ $id }}">{{ __('general.yes') }}</button>
                </div>
            `,
        });

        document.getElementById('{{ $id }}').addEventListener('shown.bs.popover', function () {
            const button = document.getElementById('{{ $id }}');
            document.getElementById('confirm_{{ $id }}').addEventListener('click', () => Livewire.dispatch('{{ $event }}', { id: button.dataset.id }))
        });

        document.getElementById('{{ $id }}').addEventListener('hide.bs.popover', function () {
            // remove all event listeners from confirmation button
            const oldConfirmationButton = document.getElementById('confirm_{{ $id }}');
            const newConfirmationButton = oldConfirmationButton.cloneNode(true);
            oldConfirmationButton.parentNode.replaceChild(newConfirmationButton, oldConfirmationButton);
        });

    </script>
</x-push>

