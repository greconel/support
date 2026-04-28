@props(['id', 'redirect'])

<script>
    $(document).ready(function() {
        $('#{{ $id }} tbody').on('dblclick', 'td', function (e) {
            if ($(this).has('a').length || $(this).hasClass('dropdown')) {
                return;
            }

            const id = $(this).closest('tr').data('id');

            let url = '{{ $redirect }}';

            url = url.replace(':id', id);

            window.location = url;
        });
    });
</script>
