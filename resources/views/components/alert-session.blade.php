{{-- SweetAlert2 Notifications --}}
@if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#00b8db',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#ff2056',
                timer: 4000,
                showConfirmButton: true,
                toast: true,
                position: 'top-end'
            });
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let messages = {!! json_encode($errors->all()) !!};
            let errorText = messages.join('\n');
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                text: errorText,
                confirmButtonColor: '#ff2056',
                timer: 4000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    </script>
@endif
