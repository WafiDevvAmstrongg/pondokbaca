<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Buku Favorit Saya</h1>
    </div>
    
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div>
        @if(count($favoriteBooks) > 0)
            <livewire:components.book-card :books="$favoriteBooks->items()" />
        @else
            <div class="col-span-full text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada buku favorit</h3>
                <p class="mt-1 text-sm text-gray-500">Tandai buku yang Anda sukai sebagai favorit.</p>
                <div class="mt-6">
                    <a href="{{ route('books') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700">
                        Lihat daftar buku
                    </a>
                </div>
            </div>
        @endif
    </div>
    
    <div class="mt-6">
        {{ $favoriteBooks->links() }}
    </div>
</div>