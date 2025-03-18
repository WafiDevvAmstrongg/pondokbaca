<aside class="w-72 bg-white/80 backdrop-blur-sm border-r border-emerald-100 fixed h-full">
    <div class="p-6">
        <!-- Logo -->
        <div class="flex items-center gap-3 mb-8">
            <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-emerald-600" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor">
                    <path
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">PondokBaca</h1>
                <p class="text-xs text-emerald-600">Perpustakaan Digital Islami</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="space-y-1">
            <a href="{{ route('home') }}"
                @class([
                    'flex items-center gap-3 px-4 py-3 rounded-xl transition-colors',
                    'bg-[#1F4B3F] hover:bg-[#2A6554] text-white' => request()->routeIs('home'),
                    'text-gray-600 hover:bg-emerald-50/50' => !request()->routeIs('home')
                ])>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="font-medium">Beranda</span>
            </a>

            <a href="{{ route('books') }}"
                @class([
                    'flex items-center gap-3 px-4 py-3 rounded-xl transition-colors',
                    'bg-[#1F4B3F] hover:bg-[#2A6554] text-white' => request()->routeIs('books'),
                    'text-gray-600 hover:bg-emerald-50/50' => !request()->routeIs('books')
                ])>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="font-medium">Daftar Buku</span>
            </a>

            <a href="{{ route('favorites') }}"
                @class([
                    'flex items-center gap-3 px-4 py-3 rounded-xl transition-colors',
                    'bg-[#1F4B3F] hover:bg-[#2A6554] text-white' => request()->routeIs('favorites'),
                    'text-gray-600 hover:bg-emerald-50/50' => !request()->routeIs('favorites')
                ])>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="font-medium">Favorit</span>
            </a>
        </nav>
    </div>
</aside>