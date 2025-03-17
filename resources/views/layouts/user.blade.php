<x-app-layout>
    <div class="min-h-screen flex flex-col">
        @livewire('layouts.navigation')
        @livewire('auth.auth-modals')
        <div class="flex flex-1">
            <!-- Desktop Sidebar -->
            <div class="hidden lg:block">
                @livewire('layouts.sidebar')
            </div>
            
            <!-- Mobile/Tablet Sidebar -->
            <div x-data="{ isOpen: false }" class="lg:hidden">
                <!-- Hamburger Button -->
                <button @click="isOpen = !isOpen" 
                        class="fixed bottom-4 left-4 z-40 bg-emerald-600 text-white p-3 rounded-full shadow-lg hover:bg-emerald-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Sidebar Overlay -->
                <div x-show="isOpen" 
                     x-transition:enter="transition-opacity ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="isOpen = false"
                     class="fixed inset-0 bg-black/50 z-30"></div>

                <!-- Sidebar Content -->
                <div x-show="isOpen"
                     x-transition:enter="transition-transform ease-out duration-300"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition-transform ease-in duration-300"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     class="fixed inset-y-0 left-0 w-72 bg-white z-40">
                    @livewire('layouts.sidebar')
                </div>
            </div>

            <!-- Main Content -->
            <main class="flex-1 lg:ml-72 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</x-app-layout>