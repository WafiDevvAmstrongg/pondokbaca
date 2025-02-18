<div>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">My Books</h2>
                <input type="text" wire:model.live="search" placeholder="Search books..." 
                       class="input input-bordered w-full max-w-xs" />
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($bookmarks as $bookmark)
                <div class="group">
                    <div class="relative aspect-[3/4] rounded-2xl overflow-hidden mb-4">
                        <img src="{{ $bookmark->buku->cover_img ? Storage::url($bookmark->buku->cover_img) : asset('images/default-book.jpg') }}" 
                             alt="{{ $bookmark->buku->judul }}" 
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                        
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                            <button class="w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center backdrop-blur-sm transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <button class="w-10 h-10 rounded-xl bg-white/20 hover:bg-white/30 flex items-center justify-center backdrop-blur-sm transition-colors text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900 line-clamp-1">{{ $bookmark->buku->judul }}</h3>
                        <p class="text-sm text-gray-600">{{ $bookmark->buku->penulis }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $bookmarks->links() }}
            </div>
        </div>
    </div>
</div> 