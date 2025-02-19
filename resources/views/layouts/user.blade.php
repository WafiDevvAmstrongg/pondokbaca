<x-app-layout>
    <div class="min-h-screen flex flex-col">
        @livewire('layouts.navigation')
        <div class="flex flex-1">
            @livewire('layouts.sidebar')
            <main class="flex-1 ml-72 p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</x-app-layout>