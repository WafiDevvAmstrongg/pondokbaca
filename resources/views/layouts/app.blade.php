<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'PondokBaca' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-[#F8FAFC]">
    {{ $slot }}
    @livewireScripts

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal', (params) => {
                Swal.fire({
                    title: params.title,
                    text: params.text,
                    icon: params.icon,
                    confirmButtonColor: '#1F4B3F',
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
</body>

</html>
