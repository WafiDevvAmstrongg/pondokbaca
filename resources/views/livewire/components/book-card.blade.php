<div>
    <!-- Book Grid -->
    @if(count($books) > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
        @foreach ($books as $book)
            <div class="group relative" wire:key="book-card-{{ is_object($book) ? $book->id : $book['id'] }}">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                     wire:click="showDetail({{ is_object($book) ? $book->id : $book['id'] }})">
                    <div class="aspect-[3/4] overflow-hidden relative">
                        <img src="{{ Storage::url(is_object($book) ? $book->cover_img : $book['cover_img']) }}" 
                             alt="{{ is_object($book) ? $book->judul : $book['judul'] }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @if((is_object($book) ? $book->stok : $book['stok']) > 0)
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
                        <h3 class="font-medium text-gray-900 mb-1 text-sm sm:text-base line-clamp-1">
                            {{ is_object($book) ? $book->judul : $book['judul'] }}
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600 mb-2 line-clamp-1">
                            {{ is_object($book) ? $book->penulis : $book['penulis'] }}
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-1.5">
                                <span class="text-yellow-400 text-base">★</span>
                                <span class="text-sm text-gray-600 font-medium">
                                    {{ number_format(is_object($book) ? ($book->ratings_avg_rating ?? 0) : ($book['ratings_avg_rating'] ?? 0), 1) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <button wire:click.stop="toggleSuka({{ is_object($book) ? $book->id : $book['id'] }})" 
                                        wire:loading.class="opacity-50"
                                        wire:loading.attr="disabled"
                                        class="text-base hover:scale-110 transition-transform {{ auth()->check() && (is_object($book) ? $book->isSukaByUser : $book['isSukaByUser']) ? 'text-red-500' : 'text-gray-300' }}">
                                    ♥
                                </button>
                                <span class="text-sm text-gray-600 font-medium">
                                    {{ is_object($book) ? ($book->suka_count ?? 0) : ($book['suka_count'] ?? 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedBook)
    <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/70">
        <div class="bg-white rounded-xl w-11/12 max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <!-- Book Details Section -->
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
                                <button wire:click="toggleSuka({{ $selectedBook->id }})"
                                        class="text-2xl hover:scale-110 transition-transform {{ $isSukaByUser ? 'text-red-500' : 'text-gray-300' }}">
                                    ♥
                                </button>
                                <span>{{ $selectedBook->suka_count }}</span>
                            </div>
                        </div>

                        <p class="text-gray-700 mb-6">{{ $selectedBook->deskripsi }}</p>

                        <div class="space-y-2">
                            <p class="text-sm text-gray-600">
                                Stok: <span class="font-medium">{{ $selectedBook->stok }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Ratings Section -->
                @if(count($ratings) > 0)
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-4">Ulasan Pembaca</h3>
                        <div class="space-y-4">
                            @foreach($ratings as $rating)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="font-medium">{{ $rating['user']['name'] }}</div>
                                        <div class="text-yellow-400">{{ str_repeat('★', $rating['rating']) }}</div>
                                    </div>
                                    <p class="text-gray-600">{{ $rating['komentar'] }}</p>
                                </div>
                            @endforeach
                        </div>
                        
                        @if(!$showAllRatings && count($ratings) > $limitRatings)
                            <button wire:click="showAllRatings" 
                                    class="mt-4 text-emerald-600 hover:text-emerald-700">
                                Lihat Semua Ulasan
                            </button>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="border-t border-gray-100 p-4 flex justify-end">
                <button wire:click="closeModal" 
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Move script to bottom and wrap in DOMContentLoaded -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showDetailModal', (data) => {
                @this.showDetail(data.bookId);
            });
        });
    });
</script>
@endpush