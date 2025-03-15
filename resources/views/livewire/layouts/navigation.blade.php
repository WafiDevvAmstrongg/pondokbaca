            <!-- Header -->
            <header class="bg-white border-b border-gray-100 sticky top-0 z-10">
                <div class="flex items-center justify-between px-8 py-4">
                    <!-- Search -->
                    <div class="w-[480px] relative search-container">
                        <div class="relative">
                            <input type="text" 
                                   wire:model.live.debounce.500ms="search" 
                                   wire:keydown.escape="closeDropdown"
                                   placeholder="Cari buku favorit Anda"
                                   class="w-full h-11 pl-11 pr-4 text-sm text-gray-700 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-primary/20 focus:bg-white transition-colors" />
                            
                            <!-- Search Icon / Loading Spinner -->
                            <div class="absolute left-3 top-1/2 -translate-y-1/2">
                                @if($isSearching)
                                    <svg class="animate-spin h-5 w-5 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                @endif
                            </div>
                        </div>

                        <!-- Search Results Dropdown -->
                        @if($showDropdown)
                            <div class="absolute w-full mt-2 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden z-50">
                                @if(count($searchResults) > 0)
                                    @foreach($searchResults as $book)
                                        <button wire:click="showBookDetail({{ $book->id }})"
                                                class="w-full flex items-center gap-3 p-3 hover:bg-gray-50 transition-colors text-left">
                                            <div class="w-12 h-16 rounded-lg overflow-hidden flex-shrink-0">
                                                <img src="{{ Storage::url($book->cover_img) }}" 
                                                     alt="{{ $book->judul }}"
                                                     class="w-full h-full object-cover">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-medium text-gray-900 truncate">{{ $book->judul }}</h4>
                                                <p class="text-sm text-gray-600 truncate">{{ $book->penulis }}</p>
                                            </div>
                                        </button>
                                    @endforeach
                                @else
                                    <div class="p-4 text-center">
                                        <div class="w-16 h-16 mx-auto mb-3 text-gray-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                      d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 text-sm">Tidak ada hasil yang ditemukan untuk "{{ $search }}"</p>
                                        <p class="text-gray-400 text-xs mt-1">Coba kata kunci lain atau periksa ejaan</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- User Menu -->
                    @auth
                        <div class="flex items-center gap-3">
                            <div class="dropdown dropdown-end">
                                <label tabindex="0"
                                    class="flex items-center gap-3 px-2 py-1 rounded-xl cursor-pointer hover:bg-gray-50">
                                    <div class="w-10 h-10 rounded-xl overflow-hidden">
                                        @if (Auth::user()->profile_img)
                                            <img src="{{ Storage::url('profiles/' . Auth::user()->profile_img) }}"
                                                alt="Profile" class="w-full h-full object-cover" />
                                        @else
                                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRglLn-gRzqwE4qOH2qXiLb1bb2KlwMO5cjRA&s"
                                                alt="Profile" class="w-full h-full object-cover" />
                                        @endif
                                    </div>
                                    <span class="font-medium">{{ Auth::user()->name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </label>
                                <ul tabindex="0"
                                    class="dropdown-content menu p-2 shadow-lg bg-white rounded-xl w-52 mt-2">
                                    <li><a href="{{ route('profile') }}" class="rounded-lg">Profile</a></li>
                                    <li>
                                        <button wire:click="logout"
                                            class="rounded-lg text-error w-full text-left">Logout</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <button wire:click="$dispatch('open-login-modal')"
                            class="btn bg-[#1F4B3F] hover:bg-[#2A6554] text-white">Masuk</button>
                    @endauth
                </div>
                {{-- <!-- Book Detail Modal -->
                @livewire('components.book-card') --}}
            </header>

