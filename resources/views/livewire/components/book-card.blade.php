<div class="bg-white p-6 rounded-lg shadow-xl">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Recommended</h2>
        <a href="#"
            class="bg-emerald-500/20 hover:bg-emerald-500/40 hover:text-emerald-600 py-2 px-4 rounded-lg text-emerald-500 font-medium transition-colors">Lihat
            Semua</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        @foreach ($books as $book)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                 wire:click="showDetail({{ $book->id }})">
                <div class="aspect-[3/4] overflow-hidden">
                    <img src="{{ Storage::url($book->cover_img) }}" 
                         alt="{{ $book->judul }}"
                         class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">{{ $book->judul }}</h3>
                    <p class="text-sm text-gray-600">{{ $book->penulis }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Detail Modal -->
    @if($showDetailModal && $selectedBook)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70">
        <div class="bg-white rounded-xl w-11/12 max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex gap-6">
                    <div class="w-1/3">
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
                            <div class="flex items-center">
                                <span class="text-red-400">♥</span>
                                <span class="ml-1">{{ $selectedBook->suka_count }}</span>
                            </div>
                        </div>

                        <p class="text-gray-700 mb-6">{{ $selectedBook->deskripsi }}</p>

                        <div class="space-y-2">
                            <p class="text-sm text-gray-600">
                                Stok: <span class="font-medium">{{ $selectedBook->stok }}</span>
                            </p>
                            <button wire:click="initiateCheckout" 
                                    class="btn btn-primary w-full"
                                    {{ $selectedBook->stok < 1 ? 'disabled' : '' }}>
                                {{ $selectedBook->stok < 1 ? 'Stok Habis' : 'Pinjam Buku' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-100 p-4 flex justify-end">
                <button wire:click="$set('showDetailModal', false)" class="btn btn-ghost">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
