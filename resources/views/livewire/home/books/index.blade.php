<div>
    <!-- Category Filter -->
    <div class="flex gap-2 sm:gap-3 overflow-x-auto pb-4 mb-6 -mx-4 px-4 sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <button wire:click="selectCategory('')"
                @class([
                    'px-4 py-2 rounded-xl text-sm whitespace-nowrap transition-colors',
                    'bg-emerald-500 text-white' => $selectedCategory === '',
                    'bg-gray-100 text-gray-600 hover:bg-gray-200' => $selectedCategory !== ''
                ])>
            Semua
        </button>
        @foreach($categories as $category)
            <button wire:click="selectCategory('{{ $category }}')"
                    @class([
                        'px-4 py-2 rounded-xl text-sm whitespace-nowrap transition-colors',
                        'bg-emerald-500 text-white' => $selectedCategory === $category,
                        'bg-gray-100 text-gray-600 hover:bg-gray-200' => $selectedCategory !== $category
                    ])>
                {{ $category }}
            </button>
        @endforeach
    </div>

    <!-- Books Grid using BookCard Component -->
    @livewire('components.book-card', ['books' => $books])

    <!-- Pagination -->
    <div class="mt-6">
        {{ $books->links() }}
    </div>
</div>