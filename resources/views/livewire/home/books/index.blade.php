<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Daftar Buku</h1>
    </div>

    <!-- Categories -->
    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
        @foreach($categories as $category)
            <button wire:click="selectCategory('{{ $category }}')"
                    class="relative group overflow-hidden">
                <div class="aspect-square rounded-xl {{ $selectedCategory === $category ? 'bg-emerald-600' : 'bg-white' }} shadow-sm hover:shadow-md transition-all p-4 flex flex-col items-center justify-center gap-3">
                    <!-- Icon sesuai kategori -->
                    <div class="{{ $selectedCategory === $category ? 'text-white' : 'text-emerald-600' }}">
                        @switch($category)
                            @case('al-quran')
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            @break
                            @case('hadis')
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            @break
                            <!-- Tambahkan icon untuk kategori lainnya -->
                            @default
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                        @endswitch
                    </div>
                    <span class="text-xs font-medium {{ $selectedCategory === $category ? 'text-white' : 'text-gray-700' }} capitalize">
                        {{ str_replace('-', ' ', $category) }}
                    </span>
                </div>
            </button>
        @endforeach
    </div>

    <!-- Books Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
        @foreach($books as $book)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                wire:click="$dispatch('showDetailModal', { bookId: {{ $book->id }} })">
                <div class="aspect-[3/4] overflow-hidden">
                    <img src="{{ Storage::url($book->cover_img) }}" 
                         alt="{{ $book->judul }}"
                         class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1 line-clamp-1">{{ $book->judul }}</h3>
                    <p class="text-sm text-gray-600 mb-2 line-clamp-1">{{ $book->penulis }}</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-yellow-400">★</span>
                            <span class="text-sm">{{ number_format($book->ratings_avg_rating, 1) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-red-400">♥</span>
                            <span class="text-sm">{{ $book->suka_count }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="py-4">
        {{ $books->links() }}
    </div>

    <!-- Book Detail Modal -->
    @livewire('components.book-card')
</div>
