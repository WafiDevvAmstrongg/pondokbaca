<x-app-layout>
    <div class="min-h-screen flex flex-col">
        @livewire('layouts.navigation')
        @livewire('auth.auth-modals')
        <div class="flex flex-1">
            <!-- Sidebar hanya tampil di desktop -->
            <div class="hidden lg:block">
                @livewire('layouts.sidebar')
            </div>
            <!-- Main content dengan padding yang menyesuaikan -->
            <main class="flex-1 lg:ml-72 p-4 sm:p-6 lg:p-8 pb-20 lg:pb-8">
                {{ $slot }}
            </main>
        </div>

        <!-- Mobile Navigation Footer -->
        <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 z-50">
            <div class="flex justify-around items-center h-16">
                <a href="{{ route('home') }}" 
                   @class([
                       'flex flex-col items-center justify-center flex-1 py-2',
                       'text-emerald-600' => request()->routeIs('home'),
                       'text-gray-500' => !request()->routeIs('home')
                   ])>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs mt-1">Beranda</span>
                </a>

                <a href="{{ route('books') }}" 
                   @class([
                       'flex flex-col items-center justify-center flex-1 py-2',
                       'text-emerald-600' => request()->routeIs('books'),
                       'text-gray-500' => !request()->routeIs('books')
                   ])>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span class="text-xs mt-1">Buku</span>
                </a>

                <a href="#" class="flex flex-col items-center justify-center flex-1 py-2 text-gray-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                    <span class="text-xs mt-1">Inspirasi</span>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>