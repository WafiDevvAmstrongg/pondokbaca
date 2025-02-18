<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PondokBaca' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-[#F8FAFC]">
    @auth
        @if(auth()->user()->isAdmin())
            <!-- Admin Layout -->
            <div class="flex h-screen">
                @livewire('admin.sidebar')
                <main class="flex-1 ml-72 flex flex-col">
                    @livewire('admin.navigation')
                    <div class="flex-1 p-8 overflow-y-auto">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        @else
            <!-- User Layout -->
            <div class="min-h-screen flex flex-col">
                @livewire('layouts.navigation')
                <div class="flex flex-1">
                    @livewire('layouts.sidebar')
                    <main class="flex-1 ml-72 p-8">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        @endif
    @else
        <!-- Guest Layout -->
        <div class="min-h-screen flex flex-col">
            @livewire('layouts.navigation')
            <div class="flex flex-1">
                @livewire('layouts.sidebar')
                <main class="flex-1 ml-72 p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    @endauth

    @livewire('auth.login')
    @livewire('auth.register')
    @livewireScripts
</body>
</html> 