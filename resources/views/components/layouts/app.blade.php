<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PondokBaca - Perpustakaan Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#F8FAFC]">
    <div class="flex h-screen">
        @livewire('layouts.sidebar')
        <main class="flex-1 ml-72">
            @livewire('layouts.navigation')
            <div class="p-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    <livewire:auth.login />
    <livewire:auth.register />

    <script>
        function switchModal(current, target) {
            document.getElementById(current).close();
            document.getElementById(target).showModal();
        }
    </script>
    

    @livewireScripts
</body>
</html>