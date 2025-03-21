<div>
    <!-- Detail Modal -->
    @if($showDetailModal && $selectedBook)
    <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/70">
        <div class="bg-white rounded-xl w-11/12 max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <!-- Info Buku -->
                <div class="flex flex-col md:flex-row gap-6 mb-8">
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
                                <span class="ml-1 text-gray-500">({{ $selectedBook->ratings->count() }} ulasan)</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="text-red-400">♥</span>
                                <span>{{ $selectedBook->suka_count }}</span>
                            </div>
                        </div>

                        <p class="text-gray-700 mb-6">{{ $selectedBook->deskripsi }}</p>

                        <!-- Info Tambahan -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="text-sm">
                                <p class="text-gray-500">Penerbit</p>
                                <p class="font-medium">{{ $selectedBook->penerbit }}</p>
                            </div>
                            <div class="text-sm">
                                <p class="text-gray-500">Tahun Terbit</p>
                                <p class="font-medium">{{ $selectedBook->tahun_terbit }}</p>
                            </div>
                            <div class="text-sm">
                                <p class="text-gray-500">ISBN</p>
                                <p class="font-medium">{{ $selectedBook->isbn }}</p>
                            </div>
                            <div class="text-sm">
                                <p class="text-gray-500">Stok</p>
                                <p class="font-medium">{{ $selectedBook->stok }}</p>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="space-y-2">
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

                <!-- Rating & Reviews Section -->
                <div class="border-t pt-6">
                    <h3 class="text-xl font-semibold mb-4">Ulasan Pembaca</h3>
                    
                    <!-- Rating Summary -->
                    <div class="flex items-center gap-8 mb-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-900">
                                {{ number_format($selectedBook->ratings_avg_rating, 1) }}
                            </div>
                            <div class="text-yellow-400 text-2xl">★</div>
                            <div class="text-sm text-gray-500">
                                {{ $selectedBook->ratings->count() }} ulasan
                            </div>
                        </div>
                        
                        <!-- Rating Distribution -->
                        <div class="flex-1">
                            @for ($i = 5; $i >= 1; $i--)
                                @php
                                    $ratingCount = $selectedBook->ratings->where('rating', $i)->count();
                                    $percentage = $selectedBook->ratings->count() > 0 
                                        ? ($ratingCount / $selectedBook->ratings->count()) * 100 
                                        : 0;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <span class="text-sm w-3">{{ $i }}</span>
                                    <span class="text-yellow-400">★</span>
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-yellow-400 rounded-full" 
                                             style="width: {{ $percentage }}%">
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-500 w-10">{{ $ratingCount }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Reviews List -->
                    <div class="space-y-6">
                        @forelse($selectedBook->ratings as $rating)
                            <div class="border-b pb-6">
                                <div class="flex items-center gap-4 mb-3">
                                    <div class="flex-shrink-0">
                                        @if($rating->user->profile_img)
                                            <img src="{{ Storage::url('profiles/' . $rating->user->profile_img) }}" 
                                                 alt="{{ $rating->user->name }}"
                                                 class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                                <span class="text-emerald-600 font-medium">
                                                    {{ strtoupper(substr($rating->user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-medium">{{ $rating->user->name }}</h4>
                                        <div class="flex items-center gap-2">
                                            <div class="text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $rating->rating)
                                                        <span>★</span>
                                                    @else
                                                        <span class="text-gray-300">★</span>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-sm text-gray-500">
                                                {{ $rating->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($rating->komentar)
                                    <p class="text-gray-700 mb-3">{{ $rating->komentar }}</p>
                                @endif

                                @if($rating->foto_review)
                                    <div class="mt-3">
                                        <img src="{{ Storage::url($rating->foto_review) }}" 
                                             alt="Review photo" 
                                             class="rounded-lg max-h-48 object-cover">
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-6 text-gray-500">
                                Belum ada ulasan untuk buku ini
                            </div>
                        @endforelse
                    </div>
                </div>
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
                            @if($showRating)
                            <div class="flex items-center gap-1.5">
                                <span class="text-yellow-400 text-base">★</span>
                                <span class="text-sm text-gray-600 font-medium">{{ number_format($book->ratings_avg_rating ?? 0, 1) }}</span>
                            </div>
                            @endif
                            @if($showLikes)
                            <div class="flex items-center gap-1.5">
                                <button wire:click.stop="toggleSuka({{ $book->id }})" 
                                        class="text-base hover:scale-110 transition-transform {{ $book->suka->where('id_user', auth()->id())->count() > 0 ? 'text-red-500' : 'text-gray-300' }}">
                                    ♥
                                </button>
                                <span class="text-sm text-gray-600 font-medium">{{ $book->suka_count ?? 0 }}</span>
                            </div>
                            @endif
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
</div>