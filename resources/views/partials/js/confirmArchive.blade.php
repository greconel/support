<script>
    function confirmArchive(id){
        let url = "{{ $url }}";
        url = url.replace(':id', id);

        Swal.fire({
            icon: 'error',
            title: "{{ __('Watch it!') }}",
            text: "{{ __('Are you sure you want to archive this?') }}",
            showCancelButton: true,
            confirmButtonText: "{{ __('Archive') }}",
            confirmButtonColor: 'red'
        }).then((result) => {
            if (result.isConfirmed){
                axios.post(url)
                    .then(() => {
                        window.LaravelDataTables["{{ $table }}"].ajax.reload();
                        Livewire.dispatch('refresh');
                    });
            }
        });
    }
</script>
