<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Recommended</h2>
        <a href="#" class="bg-emerald-500/20 hover:bg-emerald-500/40 hover:text-emerald-600 py-2 px-4 rounded-lg text-emerald-500 font-medium transition-colors">Lihat Semua</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @foreach($books as $book)
        <div class="group">
            <div class="relative aspect-[3/4] rounded-2xl overflow-hidden mb-4">
                <img src="{{ $book->cover_img }}" alt="{{ $book->judul }}" 
                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                
                <!-- Overlay with actions -->
                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                    <button class="w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center backdrop-blur-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    <button class="w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center backdrop-blur-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="space-y-2">
                <h3 class="font-medium text-gray-900 leading-tight">{{ $book->judul }}</h3>
                <p class="text-sm text-gray-600">{{ $book->penulis }}</p>
                <div class="flex items-center gap-2">
                    <div class="rating rating-sm">
                        @for($i = 1; $i <= 5; $i++)
                            <input type="radio" name="rating-{{ $book->id }}" 
                                   class="mask mask-star-2 bg-yellow-400" 
                                   {{ $i <= $book->rating ? 'checked' : '' }} disabled />
                        @endfor
                    </div>
                    <span class="text-sm text-gray-500">{{ number_format($book->rating, 1) }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>