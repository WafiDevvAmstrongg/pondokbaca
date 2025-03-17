<div class="space-y-6 sm:space-y-8">
    <!-- Header -->
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Daftar Buku</h1>
        <p class="text-gray-500">Jelajahi koleksi buku islami kami yang lengkap dan bermanfaat</p>
    </div>

    <!-- Categories -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Kategori</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <!-- Al-Quran & Hadits -->
            <button wire:click="selectCategory('al-quran')"
                    @class([
                        'relative group overflow-hidden rounded-xl transition-all duration-300',
                        'ring-2 ring-emerald-500 ring-offset-2' => $selectedCategory === 'al-quran'
                    ])>
                <div class="aspect-[4/3] p-4 bg-gradient-to-br from-emerald-50 to-emerald-100 flex flex-col items-center justify-center gap-3 group-hover:bg-emerald-100 transition-colors">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Al-Qur'an & Hadits</span>
                    @if($selectedCategory === 'al-quran')
                        <span class="absolute top-2 right-2 flex h-5 w-5 items-center justify-center">
                            <span class="animate-ping absolute h-4 w-4 rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                    @endif
                </div>
            </button>

            <!-- Fikih -->
            <button wire:click="selectCategory('fikih')"
                    @class([
                        'relative group overflow-hidden rounded-xl transition-all duration-300',
                        'ring-2 ring-emerald-500 ring-offset-2' => $selectedCategory === 'fikih'
                    ])>
                <div class="aspect-[4/3] p-4 bg-gradient-to-br from-blue-50 to-blue-100 flex flex-col items-center justify-center gap-3 group-hover:bg-blue-100 transition-colors">
                    <div class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-900">Fikih</span>
                    @if($selectedCategory === 'fikih')
                        <span class="absolute top-2 right-2 flex h-5 w-5 items-center justify-center">
                            <span class="animate-ping absolute h-4 w-4 rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                    @endif
                </div>
            </button>

            <!-- Tambahkan kategori lainnya dengan pola yang sama -->
        </div>
    </div>

    <!-- Books Grid -->
    <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ $selectedCategory ? ucwords(str_replace('-', ' ', $selectedCategory)) : 'Semua Buku' }}
                </h2>
                <p class="text-sm text-gray-500">{{ $books->total() }} buku ditemukan</p>
            </div>
            @if($selectedCategory)
                <button wire:click="selectCategory('')" 
                        class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reset Filter
                </button>
            @endif
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
            @forelse($books as $book)
                @livewire('components.book-card', ['book' => $book], key($book->id))
            @empty
                <div class="col-span-full text-center py-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada buku ditemukan</h3>
                    <p class="text-gray-500">Coba pilih kategori lain atau reset filter</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $books->links() }}
        </div>
    </div>

    <!-- Book Detail Modal -->
    @livewire('components.book-card')
</div>
