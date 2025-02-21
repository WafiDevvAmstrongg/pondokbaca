<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'PondokBaca' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireStyles
</head>

<body class="bg-[#F8FAFC]">
    {{ $slot }}
    @livewireScripts

    <script>
        // Global helper function untuk SweetAlert
        window.showAlert = function(type, message, title = '') {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
                showConfirmButton: true,
                confirmButtonColor: '#1F4B3F',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
        }

        // Global event listener untuk Livewire events
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showAlert', (data) => {
                let title = '';
                switch(data.type) {
                    case 'success':
                        title = 'Berhasil!';
                        break;
                    case 'error':
                        title = 'Gagal!';
                        break;
                    case 'warning':
                        title = 'Peringatan!';
                        break;
                    case 'info':
                        title = 'Informasi';
                        break;
                }
                showAlert(data.type, data.message, title);
            });
        });
    </script>
</body>

</html>
