@if(session($session))
    <div
        x-data
        x-init="
            Swal.fire({
                toast: true,
                icon: '{{ $icon }}',
                title: '{{ addslashes(session($session)) }}',
                position: 'bottom',
                showConfirmButton: false,
                showCloseButton: true,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (myToast) => {
                    myToast.addEventListener('mouseenter', Swal.stopTimer)
                    myToast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        "
    >
    </div>
@endif
