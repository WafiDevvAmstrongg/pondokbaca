<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800">Checkout Peminjaman</h2>
        </div>
        
        <div class="p-6">
            <!-- Info Buku -->
            <div class="flex gap-4 mb-6 p-4 bg-gray-50 rounded-xl">
                <div class="w-24">
                    <img src="{{ Storage::url($book->cover_img) }}" 
                         alt="{{ $book->judul }}"
                         class="w-full rounded-lg">
                </div>
                <div>
                    <h3 class="font-medium text-gray-900">{{ $book->judul }}</h3>
                    <p class="text-sm text-gray-600">{{ $book->penulis }}</p>
                </div>
            </div>

            <!-- Form Checkout -->
            <form wire:submit.prevent="checkout" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tanggal Peminjaman</span>
                        </label>
                        <input type="date" 
                               wire:model="tgl_peminjaman_diinginkan" 
                               min="{{ date('Y-m-d') }}"
                               class="input input-bordered" 
                               required />
                        @error('tgl_peminjaman_diinginkan') 
                            <span class="text-error text-sm mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tanggal Pengembalian</span>
                        </label>
                        <input type="date" 
                               wire:model="tgl_kembali_rencana"
                               min="{{ $tgl_peminjaman_diinginkan ?? date('Y-m-d') }}"
                               max="{{ $maxReturnDate }}"
                               class="input input-bordered" 
                               required />
                        @error('tgl_kembali_rencana') 
                            <span class="text-error text-sm mt-1">{{ $message }}</span> 
                        @enderror
                        <span class="text-sm text-gray-500 mt-1">Maksimal 7 hari peminjaman</span>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Alamat Pengiriman</span>
                    </label>
                    <textarea wire:model="alamat_pengiriman" 
                              class="textarea textarea-bordered" 
                              rows="3" required></textarea>
                    @error('alamat_pengiriman') 
                        <span class="text-error text-sm mt-1">{{ $message }}</span> 
                    @enderror
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Catatan Pengiriman (Opsional)</span>
                    </label>
                    <textarea wire:model="catatan_pengiriman" 
                              class="textarea textarea-bordered" 
                              rows="2"></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-full">
                    Ajukan Peminjaman
                </button>
            </form>
        </div>
    </div>

</div> 