<script>
    function confirmDelete(id){
        let url = "{{ $url }}";
        url = url.replace(':id', id);

        Swal.fire({
            icon: 'error',
            title: "{{ __('Hold it right there!') }}",
            text: "{{ __('Are you sure you want to delete this?') }}",
            showCancelButton: true,
            confirmButtonText: "{{ __('Yes I\'m sure, delete it!') }}",
            confirmButtonColor: 'red'
        }).then((result) => {
            if (result.isConfirmed){
                axios.delete(url)
                    .then(() => {
                        window.LaravelDataTables["{{ $table }}"].ajax.reload();
                        Livewire.dispatch('refresh');
                    });
            }
        });
    }
</script>
