<div class="space-y-8">
    <!-- Hero Banner -->
    <div class="relative w-full h-48 sm:h-64 md:h-72 lg:h-80 rounded-2xl overflow-hidden shadow-xl carousel-container">
        <!-- Carousel Slides -->
        <div class="carousel-track flex transition-transform duration-500 ease-in-out h-full">
            <!-- Slide 1 -->
            <div class="carousel-slide w-full h-full flex-shrink-0 bg-gradient-to-r from-emerald-600 to-emerald-400">
                <div class="absolute inset-0 bg-[url('/img/pattern.png')] opacity-10"></div>
                <div class="flex items-center h-full px-4 sm:px-6 lg:px-10">
                    <div class="max-w-xl">
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-2 sm:mb-4">Selamat Datang di PondokBaca</h1>
                        <p class="text-emerald-50 text-sm sm:text-base lg:text-lg mb-4 sm:mb-6">Temukan berbagai koleksi buku islami dan umum untuk menambah pengetahuan dan wawasan Anda.</p>
                        <a href="#" class="bg-white text-emerald-600 hover:bg-emerald-50 py-2 px-4 rounded-lg font-medium transition-colors inline-block text-sm sm:text-base">
                            Jelajahi Koleksi
                        </a>
                    </div>
                </div>
                <!-- Decorative Book Illustrations - Only show on larger screens -->
                <div class="hidden lg:block absolute -right-10 bottom-5 transform -rotate-12">
                    <div class="w-24 h-36 xl:w-32 xl:h-48 bg-white rounded-lg shadow-lg"></div>
                </div>
                <div class="hidden lg:block absolute right-16 bottom-10 transform rotate-6">
                    <div class="w-24 h-36 xl:w-32 xl:h-48 bg-emerald-200 rounded-lg shadow-lg"></div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-slide w-full h-full flex-shrink-0 bg-gradient-to-r from-blue-600 to-blue-400">
                <div class="absolute inset-0 bg-[url('/img/pattern.png')] opacity-10"></div>
                <div class="flex items-center h-full px-4 sm:px-6 lg:px-10">
                    <div class="max-w-xl">
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-2 sm:mb-4">Koleksi Buku Terbaru</h1>
                        <p class="text-blue-50 text-sm sm:text-base lg:text-lg mb-4 sm:mb-6">Dapatkan buku-buku terbitan terbaru dengan kualitas terbaik.</p>
                        <a href="#" class="bg-white text-blue-600 hover:bg-blue-50 py-2 px-4 rounded-lg font-medium transition-colors inline-block text-sm sm:text-base">
                            Lihat Koleksi Terbaru
                        </a>
                    </div>
                </div>
                <!-- Decorative Elements - Only show on larger screens -->
                <div class="hidden lg:block absolute right-10 bottom-10">
                    <div class="w-24 h-36 xl:w-32 xl:h-48 bg-white rounded-lg shadow-lg transform rotate-3"></div>
                    <div class="w-24 h-36 xl:w-32 xl:h-48 bg-blue-200 rounded-lg shadow-lg absolute -top-10 -left-8 transform -rotate-6"></div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-slide w-full h-full flex-shrink-0 bg-gradient-to-r from-amber-600 to-amber-400">
                <div class="absolute inset-0 bg-[url('/img/pattern.png')] opacity-10"></div>
                <div class="flex items-center h-full px-4 sm:px-6 lg:px-10">
                    <div class="max-w-xl">
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-2 sm:mb-4">Promo Bulanan</h1>
                        <p class="text-amber-50 text-sm sm:text-base lg:text-lg mb-4 sm:mb-6">Nikmati diskon spesial untuk pembelian buku-buku pilihan.</p>
                        <a href="#" class="bg-white text-amber-600 hover:bg-amber-50 py-2 px-4 rounded-lg font-medium transition-colors inline-block text-sm sm:text-base">
                            Lihat Promo
                        </a>
                    </div>
                </div>
                <!-- Decorative Elements - Only show on larger screens -->
                <div class="hidden lg:block absolute right-16 top-10">
                    <div class="w-16 h-16 xl:w-20 xl:h-20 bg-white rounded-full shadow-lg flex items-center justify-center">
                        <span class="text-amber-600 text-lg xl:text-xl font-bold">30%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Arrows -->
        <button class="carousel-arrow carousel-prev absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-emerald-600 w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-colors z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button class="carousel-arrow carousel-next absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white text-emerald-600 w-8 h-8 rounded-full flex items-center justify-center shadow-md transition-colors z-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Pagination Dots -->
        <div class="carousel-pagination absolute bottom-3 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
            <button class="carousel-dot w-2 h-2 rounded-full bg-white/50 hover:bg-white/90 transition-colors" data-index="0"></button>
            <button class="carousel-dot w-2 h-2 rounded-full bg-white/50 hover:bg-white/90 transition-colors" data-index="1"></button>
            <button class="carousel-dot w-2 h-2 rounded-full bg-white/50 hover:bg-white/90 transition-colors" data-index="2"></button>
        </div>
    </div>

    <!-- Categories Section -->
    <section class="py-4 sm:py-6">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Kategori Buku</h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
            @foreach ($categories as $category)
                <a href="#" class="bg-white rounded-xl p-3 sm:p-4 shadow-sm hover:shadow-md transition-shadow text-center group">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-2 sm:mb-3 flex items-center justify-center rounded-full bg-emerald-100 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        @switch($category)
                            @case('al-quran')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"></path>
                                </svg>
                            @break

                            @case('hadis')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                            @break

                            @case('fikih')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                    <polyline points="2 17 12 22 22 17"></polyline>
                                    <polyline points="2 12 12 17 22 12"></polyline>
                                </svg>
                            @break

                            @case('akidah')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                            @break

                            @case('sirah')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            @break

                            @case('tafsir')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 8V4H8"></path>
                                    <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect>
                                    <line x1="2" y1="10" x2="22" y2="10"></line>
                                </svg>
                            @break

                            @case('tarbiyah')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                            @break

                            @case('sejarah')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            @break

                            @case('buku-anak')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3">
                                    </path>
                                </svg>
                            @break

                            @case('novel')
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 2h-6a6 6 0 0 0-6 6v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8a6 6 0 0 0-2-6z"></path>
                                    <path d="M18 2h-6a6 6 0 0 0-6 6v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8a6 6 0 0 0-2-6z"></path>
                                </svg>
                            @break

                            @default
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                        @endswitch
                    </div>
                    <h3 class="text-sm sm:text-base font-medium text-gray-800 capitalize">{{ str_replace('-', ' ', $category) }}</h3>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Most Favorite Books Section -->
    <section class="py-4 sm:py-6">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Buku Terfavorit</h2>
            <a href="#" class="bg-emerald-500/20 hover:bg-emerald-500/40 hover:text-emerald-600 py-2 px-4 rounded-lg text-emerald-500 font-medium transition-colors text-sm sm:text-base">
                Lihat Semua
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
            @foreach ($favoriteBooks as $book)
                <div class="group relative">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                         wire:click="$dispatch('showDetailModal', { bookId: {{ $book->id }} })">
                        <div class="aspect-[3/4] overflow-hidden relative">
                            <img src="{{ Storage::url($book->cover_img) }}" 
                                 alt="{{ $book->judul }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @if($book->stok > 0)
                                <div class="absolute top-2 right-2 bg-emerald-500 text-white text-xs px-2 py-1 rounded-full">
                                    Tersedia
                                </div>
                            @else
                                <div class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                    Kosong
                                </div>
                            @endif
                        </div>
                        <div class="p-3 sm:p-4">
                            <h3 class="font-medium text-gray-900 mb-1 text-sm sm:text-base line-clamp-1">{{ $book->judul }}</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mb-2 line-clamp-1">{{ $book->penulis }}</p>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-yellow-400 text-base">★</span>
                                    <span class="text-sm text-gray-600 font-medium">{{ number_format($book->ratings_avg_rating, 1) }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <button wire:click.stop="$dispatch('toggle-suka', { bookId: {{ $book->id }} })" 
                                            class="text-base hover:scale-110 transition-transform {{ $book->isSukaBy(auth()->id()) ? 'text-red-500' : 'text-gray-300' }}">
                                        ♥
                                    </button>
                                    <span class="text-sm text-gray-600 font-medium">{{ $book->suka_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Top Rated Books Section -->
    <section class="py-4 sm:py-6">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Buku Rating Tertinggi</h2>
            <a href="#" class="bg-emerald-500/20 hover:bg-emerald-500/40 hover:text-emerald-600 py-2 px-4 rounded-lg text-emerald-500 font-medium transition-colors text-sm sm:text-base">
                Lihat Semua
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
            @foreach ($topRatedBooks as $book)
                <div class="group relative">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                         wire:click="$dispatch('showDetailModal', { bookId: {{ $book->id }} })">
                        <div class="aspect-[3/4] overflow-hidden relative">
                            <img src="{{ Storage::url($book->cover_img) }}" 
                                 alt="{{ $book->judul }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @if($book->stok > 0)
                                <div class="absolute top-2 right-2 bg-emerald-500 text-white text-xs px-2 py-1 rounded-full">
                                    Tersedia
                                </div>
                            @else
                                <div class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                    Kosong
                                </div>
                            @endif
                        </div>
                        <div class="p-3 sm:p-4">
                            <h3 class="font-medium text-gray-900 mb-1 text-sm sm:text-base line-clamp-1">{{ $book->judul }}</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mb-2 line-clamp-1">{{ $book->penulis }}</p>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-yellow-400 text-base">★</span>
                                    <span class="text-sm text-gray-600 font-medium">{{ number_format($book->ratings_avg_rating, 1) }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <button wire:click.stop="$dispatch('toggle-suka', { bookId: {{ $book->id }} })" 
                                            class="text-base hover:scale-110 transition-transform {{ $book->isSukaBy(auth()->id()) ? 'text-red-500' : 'text-gray-300' }}">
                                        ♥
                                    </button>
                                    <span class="text-sm text-gray-600 font-medium">{{ $book->suka_count }}</span>
                                </div>
                            </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const track = document.querySelector('.carousel-track');
            const slides = document.querySelectorAll('.carousel-slide');
            const nextButton = document.querySelector('.carousel-next');
            const prevButton = document.querySelector('.carousel-prev');
            const dots = document.querySelectorAll('.carousel-dot');

            let currentIndex = 0;
            const slideWidth = slides[0].getBoundingClientRect().width;
            const slidesCount = slides.length;

            // Set active dot
            function updateDots() {
                dots.forEach((dot, index) => {
                    if (index === currentIndex) {
                        dot.classList.add('bg-white', 'scale-125');
                        dot.classList.remove('bg-white/50');
                    } else {
                        dot.classList.remove('bg-white', 'scale-125');
                        dot.classList.add('bg-white/50');
                    }
                });
            }

            // Initial setup
            updateDots();

            // Auto slide function
            let autoSlideInterval;

            function startAutoSlide() {
                autoSlideInterval = setInterval(() => {
                    currentIndex = (currentIndex + 1) % slidesCount;
                    updateSlide();
                }, 5000); // Change slide every 5 seconds
            }

            function stopAutoSlide() {
                clearInterval(autoSlideInterval);
            }

            // Move to specific slide
            function updateSlide() {
                track.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
                updateDots();
            }

            // Next slide
            nextButton.addEventListener('click', function() {
                stopAutoSlide();
                currentIndex = (currentIndex + 1) % slidesCount;
                updateSlide();
                startAutoSlide();
            });

            // Previous slide
            prevButton.addEventListener('click', function() {
                stopAutoSlide();
                currentIndex = (currentIndex - 1 + slidesCount) % slidesCount;
                updateSlide();
                startAutoSlide();
            });

            // Dot navigation
            dots.forEach((dot, index) => {
                dot.addEventListener('click', function() {
                    stopAutoSlide();
                    currentIndex = index;
                    updateSlide();
                    startAutoSlide();
                });
            });

            // Touch events for swipe
            let touchStartX = 0;
            let touchEndX = 0;

            track.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
                stopAutoSlide();
            }, {
                passive: true
            });

            track.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
                startAutoSlide();
            }, {
                passive: true
            });

            function handleSwipe() {
                const swipeDistance = touchStartX - touchEndX;
                if (swipeDistance > 50) {
                    // Swipe left, go next
                    currentIndex = (currentIndex + 1) % slidesCount;
                } else if (swipeDistance < -50) {
                    // Swipe right, go previous
                    currentIndex = (currentIndex - 1 + slidesCount) % slidesCount;
                }
                updateSlide();
            }

            // Start auto slide
            startAutoSlide();

            // Stop auto slide when hovering over carousel
            const container = document.querySelector('.carousel-container');
            container.addEventListener('mouseenter', stopAutoSlide);
            container.addEventListener('mouseleave', startAutoSlide);
        });
    </script>
</div>