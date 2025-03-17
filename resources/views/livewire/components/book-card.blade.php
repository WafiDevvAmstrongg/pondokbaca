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
                            <button wire:click="toggleSuka({{ $selectedBook->id }})" 
                                    class="flex items-center gap-1 transition-colors {{ $isSukaByUser ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}">
                                <span class="text-2xl">♥</span>
                                <span>{{ $selectedBook->suka_count }}</span>
                            </button>
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

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('showDetailModal', (data) => {
                @this.showDetail(data.bookId);
            });
        });
    </script>
</div>