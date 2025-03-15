<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Daftar Buku</h1>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="flex gap-4">
            <!-- Search -->
            <div class="flex-1">
                <input type="text" 
                       wire:model.live="search" 
                       placeholder="Cari judul atau penulis..."
                       class="w-full h-11 px-4 text-sm text-gray-700 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-white transition-colors" />
            </div>

            <!-- Category Filter -->
            <div class="w-64">
                <select wire:model.live="selectedCategory"
                        class="w-full h-11 px-4 text-sm text-gray-700 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-white transition-colors">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ ucwords(str_replace('-', ' ', $category)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
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
