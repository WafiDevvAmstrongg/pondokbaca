<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite('resources/css/app.css')
    @livewireStyles
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-[#FBF7F4]">
    <div x-data="{ scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)">
        <!-- Navbar -->
        <livewire:layouts.navigation />

        <!-- Page Content -->
        <main class="py-4">
            {{ $slot }}
        </main>
    </div>

    <livewire:auth.login />
    <livewire:auth.register />
    
    @livewireScripts
</body>
</html>