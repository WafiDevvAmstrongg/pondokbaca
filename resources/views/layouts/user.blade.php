<x-app-layout>
    <div class="min-h-screen flex flex-col">
        @livewire('layouts.navigation')
        @livewire('auth.auth-modals')
        <div class="flex flex-1">
            <!-- Desktop Sidebar -->
            <div class="hidden lg:block">
                @livewire('layouts.sidebar')
            </div>

            <!-- Main Content -->
            <main class="flex-1 lg:ml-72 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</x-app-layout>