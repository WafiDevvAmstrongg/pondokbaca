{{-- resources/views/layouts/app.blade.php --}}
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
        <!-- Main Content -->
        <main class="flex-1 ml-72">
            @livewire('layouts.navigation')
            <div class="p-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>