<div class="space-y-8">
    <!-- Hero Banner -->
    <div class="relative w-full h-80 bg-gradient-to-r from-emerald-600 to-emerald-400 rounded-2xl overflow-hidden shadow-xl">
        <div class="absolute inset-0 bg-[url('/img/pattern.png')] opacity-10"></div>
        <div class="flex items-center h-full px-10 sm:px-16">
            <div class="max-w-2xl">
                <h1 class="text-4xl sm:text-5xl font-bold text-white mb-4">Selamat Datang di PondokBaca</h1>
                <p class="text-emerald-50 text-lg mb-6">Temukan berbagai koleksi buku islami dan umum untuk menambah pengetahuan dan wawasan Anda.</p>
                <a href="#" class="bg-white text-emerald-600 hover:bg-emerald-50 py-3 px-6 rounded-lg font-medium transition-colors inline-block">
                    Jelajahi Koleksi
                </a>
            </div>
        </div>
        <!-- Decorative Book Illustrations -->
        <div class="hidden lg:block absolute -right-10 bottom-5 transform -rotate-12">
            <div class="w-40 h-56 bg-white rounded-lg shadow-lg"></div>
        </div>
        <div class="hidden lg:block absolute right-20 bottom-10 transform rotate-6">
            <div class="w-40 h-56 bg-emerald-200 rounded-lg shadow-lg"></div>
        </div>
    </div>

    <!-- Categories Section -->
    <section class="py-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Kategori Buku</h2>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach ($categories as $category)
                <a href="#" 
                   class="bg-white rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow text-center group">
                    <div class="w-16 h-16 mx-auto mb-3 flex items-center justify-center rounded-full bg-emerald-100 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        @switch($category)
                            @case('al-quran')
                                <img src="https://static.vecteezy.com/system/resources/previews/022/961/544/non_2x/3d-islamic-quran-icon-illustration-object-png.png"  class="w-8 h-8" alt="">
                                @break
                            @case('hadis')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                @break
                            @case('fikih')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                                @break
                            @case('akidah')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                                @break
                            @case('sirah')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                @break
                            @case('tafsir')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4H8"></path><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                                @break
                            @case('tarbiyah')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                                @break
                            @case('sejarah')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                @break
                            @case('buku-anak')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"></path></svg>
                                @break
                            @case('novel')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-6a6 6 0 0 0-6 6v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8a6 6 0 0 0-2-6z"></path><path d="M18 2h-6a6 6 0 0 0-6 6v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8a6 6 0 0 0-2-6z"></path></svg>
                                @break
                            @default
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        @endswitch
                    </div>
                    <h3 class="font-medium text-gray-800 capitalize">{{ str_replace('-', ' ', $category) }}</h3>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Most Favorite Books Section -->
    <section class="py-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Buku Terfavorit</h2>
            <a href="#"
                class="bg-emerald-500/20 hover:bg-emerald-500/40 hover:text-emerald-600 py-2 px-4 rounded-lg text-emerald-500 font-medium transition-colors">Lihat Semua</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            @foreach ($favoriteBooks as $book)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                     wire:click="$dispatch('showDetailModal', { bookId: {{ $book->id }} })">
                    <div class="aspect-[3/4] overflow-hidden">
                        <img src="{{ Storage::url($book->cover_img) }}" 
                             alt="{{ $book->judul }}"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900 mb-1">{{ $book->judul }}</h3>
                        <p class="text-sm text-gray-600">{{ $book->penulis }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-red-400">♥</span>
                            <span class="ml-1 text-sm">{{ $book->suka_count }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Top Rated Books Section -->
    <section class="py-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Buku Rating Tertinggi</h2>
            <a href="#"
                class="bg-emerald-500/20 hover:bg-emerald-500/40 hover:text-emerald-600 py-2 px-4 rounded-lg text-emerald-500 font-medium transition-colors">Lihat Semua</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            @foreach ($topRatedBooks as $book)
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                     wire:click="$dispatch('showDetailModal', { bookId: {{ $book->id }} })">
                    <div class="aspect-[3/4] overflow-hidden">
                        <img src="{{ Storage::url($book->cover_img) }}" 
                             alt="{{ $book->judul }}"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900 mb-1">{{ $book->judul }}</h3>
                        <p class="text-sm text-gray-600">{{ $book->penulis }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-yellow-400">★</span>
                            <span class="ml-1 text-sm">{{ number_format($book->ratings_avg_rating, 1) }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Include Book Card Component for Detail Modal -->
    <div>
        @livewire('components.book-card')
    </div>
</div>