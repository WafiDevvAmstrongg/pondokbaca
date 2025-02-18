<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PondokBaca</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#F8FAFC]">
    <div class="flex h-screen">
        @livewire('admin.sidebar')
        <main class="flex-1 ml-72 flex flex-col">
            @livewire('admin.navigation')
            <div class="flex-1 p-8 overflow-y-auto">
                {{ $slot }}
            </div>
        </main>
    </div>
    @livewireScripts
</body>
</html> 