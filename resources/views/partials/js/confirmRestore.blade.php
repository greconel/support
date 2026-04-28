<script>
    function confirmRestore(id){
        let url = "{{ $url }}";
        url = url.replace(':id', id);

        Swal.fire({
            icon: 'success',
            title: "{{ __('Hey there!') }}",
            text: "{{ __('Are you sure you want to restore this?') }}",
            showCancelButton: true,
            confirmButtonText: "{{ __('I\'m sure!') }}",
            confirmButtonColor: 'green'
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
