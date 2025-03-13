<div>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Data Buku</h2>
                <button wire:click="create" class="btn btn-primary">Tambah Buku</button>
            </div>
            <div class="mt-4">
                <input type="text" wire:model.live="search" placeholder="Cari buku..." 
                       class="input input-bordered w-full max-w-xs" />
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cover</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                        <tr>
                            <td>
                                <div class="w-16 h-20 rounded-lg overflow-hidden">
                                    <img src="{{ $book->cover_img ? Storage::url($book->cover_img) : asset('images/default-book.jpg') }}" 
                                         alt="{{ $book->judul }}" 
                                         class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td>
                                <div>
                                    <p class="font-medium">{{ $book->judul }}</p>
                                    <p class="text-sm text-gray-500">ISBN: {{ $book->isbn ?? 'N/A' }}</p>
                                </div>
                            </td>
                            <td>{{ $book->penulis }}</td>
                            <td>
                                <span class="badge badge-ghost">{{ $book->kategori }}</span>
                            </td>
                            <td>{{ $book->stok }}</td>
                            <td>
                                <button wire:click="edit({{ $book->id }})" class="btn btn-sm btn-ghost">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="delete({{ $book->id }})" class="btn btn-sm btn-ghost text-error">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $books->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <dialog class="modal" {{ $showModal ? 'open' : '' }}>
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">{{ $bukuId ? 'Edit Book' : 'Add New Book' }}</h3>
            <form wire:submit.prevent="save">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Judul</span>
                    </label>
                    <input type="text" wire:model="judul" class="input input-bordered" required />
                    @error('judul') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Penulis</span>
                    </label>
                    <input type="text" wire:model="penulis" class="input input-bordered" required />
                    @error('penulis') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">ISBN</span>
                    </label>
                    <input type="text" wire:model="isbn" class="input input-bordered" />
                    @error('isbn') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Kategori</span>
                    </label>
                    <select wire:model="kategori" class="select select-bordered" required>
                        <option value="">Pilih kategori</option>
                        <option value="al-quran">Al-Quran</option>
                        <option value="hadist">Hadist</option>
                        <option value="fiqih">Fiqih</option>
                        <option value="akidah">Akidah</option>
                        <option value="sirah">Sirah</option>
                        <option value="tafsir">Tafsir</option>
                        <option value="tarbiyah">Tarbiyah</option>
                        <option value="sejarah">Sejarah</option>
                        <option value="buku-anak">Buku Anak</option>
                        <option value="novel">Novel</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    @error('kategori') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Deskripsi</span>
                    </label>
                    <textarea wire:model="deskripsi" class="textarea textarea-bordered" rows="3"></textarea>
                    @error('deskripsi') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text">Cover Buku</span>
                    </label>
                    <input type="file" wire:model="cover_img" class="file-input file-input-bordered" accept="image/*" />
                    @error('cover_img') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    
                    @if ($cover_img)
                        <img src="{{ $cover_img->temporaryUrl() }}" class="mt-2 w-32 h-40 object-cover rounded-lg">
                    @elseif ($temp_cover_img)
                        <img src="{{ Storage::url($temp_cover_img) }}" class="mt-2 w-32 h-40 object-cover rounded-lg">
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Stok</span>
                        </label>
                        <input type="number" wire:model="stok" class="input input-bordered" required min="0" />
                        @error('stok') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Denda Harian</span>
                        </label>
                        <input type="number" wire:model="denda_harian" class="input input-bordered" required min="0" />
                        @error('denda_harian') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Penerbit</span>
                        </label>
                        <input type="text" wire:model="penerbit" class="input input-bordered" />
                        @error('penerbit') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tahun Terbit</span>
                        </label>
                        <input type="text" wire:model="tahun_terbit" class="input input-bordered" />
                        @error('tahun_terbit') <span class="text-error text-sm mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn" wire:click="$toggle('showModal')">Cancel</button>
                </div>
            </form>
        </div>
    </dialog>
</div> 