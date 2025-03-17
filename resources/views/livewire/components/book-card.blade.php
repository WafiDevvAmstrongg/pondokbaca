<div>
    <!-- Detail Modal -->
    @if($showDetailModal && $selectedBook)
    <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/70">
        <div class="bg-white rounded-xl w-11/12 max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/3">
                        <img src="{{ Storage::url($selectedBook->cover_img) }}" 
                             alt="{{ $selectedBook->judul }}"
                             class="w-full rounded-lg">
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-semibold mb-2">{{ $selectedBook->judul }}</h2>
                        <p class="text-gray-600 mb-4">{{ $selectedBook->penulis }}</p>
                        
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex items-center">
                                <span class="text-yellow-400">★</span>
                                <span class="ml-1">{{ number_format($selectedBook->ratings_avg_rating, 1) }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="text-red-400">♥</span>
                                <span>{{ $selectedBook->suka_count }}</span>
                            </div>
                        </div>

                        <p class="text-gray-700 mb-6">{{ $selectedBook->deskripsi }}</p>

                        <div class="space-y-2">
                            <p class="text-sm text-gray-600">
                                Stok: <span class="font-medium">{{ $selectedBook->stok }}</span>
                            </p>
                            <button wire:click="initiateCheckout" 
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white py-2 px-4 rounded w-full transition-colors"
                                    {{ $selectedBook->stok < 1 ? 'disabled' : '' }}>
                                {{ $selectedBook->stok < 1 ? 'Stok Habis' : 'Pinjam Buku' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-100 p-4 flex justify-end">
                <button wire:click="closeModal" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Book Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
        @foreach ($books as $book)
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
                                Dipinjam
                            </div>
                        @endif
                    </div>
                    <div class="p-3 sm:p-4">
                        <h3 class="font-medium text-gray-900 mb-1 text-sm sm:text-base line-clamp-1">{{ $book->judul }}</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mb-2 line-clamp-1">{{ $book->penulis }}</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1">
                                    <span class="text-yellow-400 text-sm">★</span>
                                    <span class="text-xs text-gray-600">{{ number_format($book->ratings_avg_rating, 1) }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button wire:click.stop="toggleSuka({{ $book->id }})" 
                                            class="text-sm {{ $book->isSukaBy(auth()->id()) ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}">
                                        ♥
                                    </button>
                                    <span class="text-xs text-gray-600">{{ $book->suka_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showDetailModal', (data) => {
                @this.showDetail(data.bookId);
            });
        });
    </script>
</div>