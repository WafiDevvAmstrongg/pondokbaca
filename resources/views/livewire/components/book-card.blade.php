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
                            <span class="text-red-400">♥</span>
                            <span>{{ $selectedBook->suka_count }}</span>
                        </div>
                    </div>

                    <p class="text-gray-700 mb-6">{{ $selectedBook->deskripsi }}</p>

                    <div class="space-y-2">
                        <p class="text-sm text-gray-600">
                            Stok: <span class="font-medium">{{ $selectedBook->stok }}</span>
                        </p>
                        @auth
                            @php
                                $totalDenda = \App\Models\Peminjaman::where('id_user', auth()->id())
                                    ->where(function($query) {
                                        $query->where('status', 'terlambat')
                                            ->orWhere('total_denda', '>', 0);
                                    })
                                    ->sum('total_denda');
                            @endphp

                            @if($totalDenda > 0)
                                <div class="alert alert-error">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <h4 class="font-bold">Anda memiliki denda yang belum dibayar!</h4>
                                        <p>Total Denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
                                        <p class="text-sm">Silahkan lunasi denda terlebih dahulu.</p>
                                        <a href="{{ route('user.pembayaran') }}" class="btn btn-sm btn-neutral mt-2">Bayar Denda</a>
                                    </div>
                                </div>
                            @else
                                <button wire:click="initiateCheckout" 
                                        class="bg-emerald-600 hover:bg-emerald-700 text-white py-2 px-4 rounded w-full transition-colors"
                                        {{ $selectedBook->stok < 1 ? 'disabled' : '' }}>
                                    {{ $selectedBook->stok < 1 ? 'Stok Habis' : 'Pinjam Buku' }}
                                </button>
                            @endif
                        @else
                        <div class="alert alert-warning mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <h4 class="font-bold">Anda belum login!</h4>
                                <p class="text-sm">Silahkan login terlebih dahulu untuk meminjam buku.</p>
                            </div>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Ratings Section -->
            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-semibold mb-4">Ulasan Pembaca ({{ count($ratings) }})</h3>
                
                @if(count($ratings) > 0)
                    <div class="space-y-6">
                        @foreach($ratings as $rating)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mr-4">
                                        @if(isset($rating['user']) && isset($rating['user']['profile_photo_path']))
                                            <img src="{{ Storage::url($rating['user']['profile_photo_path']) }}" 
                                                 alt="{{ $rating['user']['name'] }}" 
                                                 class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500 font-semibold">
                                                    {{ isset($rating['user']) ? substr($rating['user']['name'], 0, 1) : '?' }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <h4 class="font-medium">{{ isset($rating['user']) ? $rating['user']['name'] : 'Pengguna' }}</h4>
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <span class="text-{{ $i <= $rating['rating'] ? 'yellow' : 'gray' }}-400">★</span>
                                                    @endfor
                                                    <span class="ml-2 text-sm text-gray-500">
                                                        {{ \Carbon\Carbon::parse($rating['created_at'])->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if(isset($rating['komentar']))
                                            <p class="text-gray-700 mt-2">{{ $rating['komentar'] }}</p>
                                        @endif
                                        
                                        @if(isset($rating['foto_review']))
                                            <div class="mt-3">
                                                <img src="{{ Storage::url($rating['foto_review']) }}" 
                                                     alt="Review photo" 
                                                     class="max-h-48 rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                                     onclick="window.open('{{ Storage::url($rating['foto_review']) }}', '_blank')">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if(count($this->ratings) > $this->limitRatings && !$showAllRatings)
                        <div class="mt-4 text-center">
                            <button wire:click="showAllRatings" 
                                    class="text-blue-600 hover:text-blue-800 font-medium">
                                Lihat semua ulasan ({{ count($this->ratings) }})
                            </button>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>Belum ada ulasan untuk buku ini.</p>
                    </div>
                @endif
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
@if(count($books) > 0)
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
    @foreach ($books as $book)
        <div class="group relative">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                 wire:click="showDetail({{ $book->id }})">
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
                            <span class="text-sm text-gray-600 font-medium">{{ number_format($book->ratings_avg_rating ?? 0, 1) }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button wire:click.stop="$dispatch('toggle-suka', { bookId: {{ $book->id }} })" 
                                    class="text-base hover:scale-110 transition-transform {{ method_exists($book, 'isSukaBy') && $book->isSukaBy(auth()->id()) ? 'text-red-500' : 'text-gray-300' }}">
                                ♥
                            </button>
                            <span class="text-sm text-gray-600 font-medium">{{ $book->suka_count ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('showDetailModal', (data) => {
            @this.showDetail(data.bookId);
        });
    });
</script>