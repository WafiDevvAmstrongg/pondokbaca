<div>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-semibold text-gray-800">Data Buku</h2>
                <button wire:click="create" class="btn btn-primary w-full sm:w-auto">Tambah Buku</button>
            </div>
            <div class="mt-4">
                <input type="text" wire:model.live="search" placeholder="Cari buku..." 
                       class="input input-bordered w-full max-w-xs" />
            </div>
        </div>
        
        @if ($showSuccessNotification)
        <div id="successNotification" class="bg-green-50 border-l-4 border-green-500 p-4 m-4 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ $notificationMessage }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="p-4">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b border-gray-100">
                        <th class="pb-4 font-medium text-gray-400">Buku</th>
                        <th class="pb-4 font-medium text-gray-400 hidden sm:table-cell">Penulis</th>
                        <th class="pb-4 font-medium text-gray-400 hidden lg:table-cell">ISBN</th>
                        <th class="pb-4 font-medium text-gray-400 hidden md:table-cell">Kategori</th>
                        <th class="pb-4 font-medium text-gray-400">Stok</th>
                        <th class="pb-4 font-medium text-gray-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($books as $book)
                    <tr>
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-16 rounded-lg overflow-hidden bg-gray-100">
                                    @if($book->cover_img)
                                        <img src="{{ Storage::url($book->cover_img) }}" 
                                             alt="{{ $book->judul }}"
                                             class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div>
                                    <span class="font-medium">{{ $book->judul }}</span>
                                    <span class="block text-sm text-gray-500 sm:hidden">{{ $book->penulis }}</span>
                                    <span class="block text-sm text-gray-500 md:hidden">{{ $book->kategori }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 hidden sm:table-cell">{{ $book->penulis }}</td>
                        <td class="py-4 hidden lg:table-cell">{{ $book->isbn }}</td>
                        <td class="py-4 hidden md:table-cell">
                            <span class="badge badge-ghost">{{ $book->kategori }}</span>
                        </td>
                        <td class="py-4">{{ $book->stok }}</td>
                        <td class="py-4">
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $book->id }})" class="btn btn-sm btn-ghost">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $book->id }})" class="btn btn-sm btn-ghost text-error">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $books->links() }}
        </div>
    </div>

    @if($showModal)
    <dialog class="modal modal-open">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">{{ $bukuId ? 'Edit Buku' : 'Tambah Buku' }}</h3>
            <form wire:submit="save">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Judul</span>
                    </label>
                    <input type="text" wire:model.defer="judul" class="input input-bordered" required />
                    @error('judul')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Penulis</span>
                    </label>
                    <input type="text" wire:model.defer="penulis" class="input input-bordered" required />
                    @error('penulis')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">ISBN</span>
                    </label>
                    <input type="text" wire:model.defer="isbn" class="input input-bordered" />
                    @error('isbn')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Kategori</span>
                    </label>
                    <select wire:model.defer="kategori" class="select select-bordered" required>
                        <option value="">Pilih kategori</option>
                        <option value="al-quran">Al-Quran</option>
                        <option value="hadis">Hadis</option>
                        <option value="fikih">Fikih</option>
                        <option value="akidah">Akidah</option>
                        <option value="sirah">Sirah</option>
                        <option value="tafsir">Tafsir</option>
                        <option value="tarbiyah">Tarbiyah</option>
                        <option value="sejarah">Sejarah</option>
                        <option value="buku-anak">Buku Anak</option>
                        <option value="novel">Novel</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    @error('kategori')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Deskripsi</span>
                    </label>
                    <textarea wire:model.defer="deskripsi" class="textarea textarea-bordered" rows="3"></textarea>
                    @error('deskripsi')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Cover Buku</span>
                    </label>
                    <input type="file" wire:model="cover_img" class="file-input file-input-bordered"
                        accept="image/*" />
                    @error('cover_img')
                        <span class="text-error text-sm mt-1">{{ $message }}</span>
                    @enderror

                    <div class="mt-2">
                        @if ($cover_img)
                            <img src="{{ $cover_img->temporaryUrl() }}"
                                class="w-32 h-40 object-cover rounded-lg">
                        @elseif ($temp_cover_img)
                            <img src="{{ Storage::url($temp_cover_img) }}"
                                class="w-32 h-40 object-cover rounded-lg">
                            <p class="text-sm text-gray-500 mt-1">Cover saat ini. Upload baru untuk mengubah.</p>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Stok</span>
                        </label>
                        <input type="number" wire:model.defer="stok" class="input input-bordered" required
                            min="0" />
                        @error('stok')
                            <span class="text-error text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Denda Harian</span>
                        </label>
                        <input type="number" wire:model.defer="denda_harian" class="input input-bordered" required
                            min="0" />
                        @error('denda_harian')
                            <span class="text-error text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Penerbit</span>
                        </label>
                        <input type="text" wire:model.defer="penerbit" class="input input-bordered" />
                        @error('penerbit')
                            <span class="text-error text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tahun Terbit</span>
                        </label>
                        <input type="number" wire:model.defer="tahun_terbit" class="input input-bordered" />
                        @error('tahun_terbit')
                            <span class="text-error text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn" wire:click="$set('showModal', false)">Batal</button>
                </div>
            </form>
        </div>
    </dialog>
    @endif

    <!-- Scripts for handling notifications -->
    <script>
    document.addEventListener('livewire:initialized', () => {
        // Property updated event listener (existing code)
        Livewire.on('propertyUpdated', () => {
            console.log('Properti telah diperbarui');
        });
        
        // Auto-hide success notification after 3 seconds
        Livewire.on('hideSuccessNotification', () => {
            setTimeout(() => {
                @this.showSuccessNotification = false;
            }, 3000);
        });
    });
    </script>
</div>