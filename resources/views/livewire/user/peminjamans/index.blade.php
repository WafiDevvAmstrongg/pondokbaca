<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-semibold mb-6">Peminjaman Saya</h2>

                <!-- Filter dan Search -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="form-control">
                        <div class="input-group">
                            <input type="text" wire:model.live="search" placeholder="Cari judul buku..."
                                class="input input-bordered w-full" />
                        </div>
                    </div>
                    <select wire:model.live="status" class="select select-bordered w-full">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="diproses">Diproses</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="dipinjam">Dipinjam</option>
                        <option value="dikembalikan">Dikembalikan</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="selesai">Selesai</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>

                <!-- Daftar Peminjaman - Card View untuk Mobile -->
                <div class="grid grid-cols-1 gap-4 md:hidden">
                    @forelse($loans as $loan)
                        <div class="card bg-base-100 shadow-sm">
                            <div class="card-body p-4">
                                <div class="flex items-center gap-4">
                                    @if ($loan->buku->cover_img)
                                        <div class="avatar">
                                            <div class="mask mask-squircle w-12 h-12">
                                                <img src="{{ Storage::url($loan->buku->cover_img) }}"
                                                    alt="Cover {{ $loan->buku->judul }}" />
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h3 class="font-bold">{{ $loan->buku->judul }}</h3>
                                        <p class="text-sm opacity-50">{{ $loan->buku->penulis }}</p>
                                    </div>
                                </div>

                                <div class="divider my-2"></div>

                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div>Tanggal Pinjam:</div>
                                    <div>
                                        {{ $loan->tgl_peminjaman_diinginkan ? $loan->tgl_peminjaman_diinginkan->format('d/m/Y') : '-' }}
                                    </div>

                                    <div>Tenggat:</div>
                                    <div>
                                        {{ $loan->tgl_kembali_rencana ? $loan->tgl_kembali_rencana->format('d/m/Y') : '-' }}
                                    </div>

                                    <div>Status:</div>
                                    <div>
                                        <div
                                            class="badge {{ match ($loan->status) {
                                                'pending' => 'badge-warning',
                                                'diproses' => 'badge-info',
                                                'dikirim' => 'badge-primary',
                                                'dipinjam' => 'badge-success',
                                                'dikembalikan' => 'badge-success',
                                                'terlambat' => 'badge-error',
                                                'selesai' => 'badge-success',
                                                'ditolak' => 'badge-error',
                                                default => 'badge-ghost',
                                            } }}">
                                            {{ ucfirst($loan->status) }}
                                        </div>
                                    </div>
                                </div>

                                @if ($loan->status === 'ditolak')
                                    <div class="mt-2 text-sm text-error">
                                        {{ $loan->alasan_penolakan }}
                                    </div>
                                @elseif($loan->status === 'terlambat')
                                    <div class="mt-2 text-sm text-error">
                                        Denda: Rp {{ number_format($loan->total_denda, 0, ',', '.') }}
                                    </div>
                                @elseif($loan->status === 'diproses')
                                    <div class="mt-2 text-sm">
                                        No. Resi: {{ $loan->nomor_resi }}
                                    </div>
                                @endif

                                <div class="mt-3 flex justify-end">
                                    @if ($loan->status === 'dipinjam')
                                        <button wire:click="returnBook({{ $loan->id }})"
                                            class="btn btn-sm btn-primary">
                                            Kembalikan
                                        </button>
                                    @elseif($loan->status === 'dikembalikan' && !$loan->hasRating)
                                        <button wire:click="showRatingForm({{ $loan->id }})"
                                            class="btn btn-sm btn-secondary">
                                            Beri Rating
                                        </button>
                                    @elseif($loan->bukti_pengiriman)
                                        <button wire:click="showProof({{ $loan->id }})"
                                            class="btn btn-sm btn-outline">
                                            Lihat Bukti
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            Tidak ada data peminjaman
                        </div>
                    @endforelse
                </div>

                <!-- Daftar Peminjaman - Table View untuk Desktop -->
                <div class="hidden md:block">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tenggat</th>
                                <th>Status</th>
                                <th>Info</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loans as $index => $loan)
                                <tr>
                                    <td>{{ $loans->firstItem() + $index }}</td>
                                    <td>
                                        <div class="flex items-center space-x-3">
                                            @if ($loan->buku->cover_img)
                                                <div class="avatar">
                                                    <div class="mask mask-squircle w-12 h-12">
                                                        <img src="{{ Storage::url($loan->buku->cover_img) }}"
                                                            alt="Cover {{ $loan->buku->judul }}" />
                                                    </div>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-bold">{{ $loan->buku->judul }}</div>
                                                <div class="text-sm opacity-50">{{ $loan->buku->penulis }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $loan->tgl_peminjaman_diinginkan ? $loan->tgl_peminjaman_diinginkan->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>{{ $loan->tgl_kembali_rencana ? $loan->tgl_kembali_rencana->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        <div
                                            class="badge {{ match ($loan->status) {
                                                'pending' => 'badge-warning',
                                                'diproses' => 'badge-info',
                                                'dikirim' => 'badge-primary',
                                                'dipinjam' => 'badge-success',
                                                'dikembalikan' => 'badge-success',
                                                'terlambat' => 'badge-error',
                                                'selesai' => 'badge-success',
                                                'ditolak' => 'badge-error',
                                                default => 'badge-ghost',
                                            } }}">
                                            {{ ucfirst($loan->status) }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($loan->status === 'ditolak')
                                            <div class="text-sm text-error">
                                                {{ $loan->alasan_penolakan }}
                                            </div>
                                        @elseif($loan->status === 'terlambat')
                                            <div class="text-sm text-error">
                                                Denda: Rp {{ number_format($loan->total_denda, 0, ',', '.') }}
                                            </div>
                                        @elseif($loan->status === 'diproses')
                                            <div class="text-sm">
                                                No. Resi: {{ $loan->nomor_resi }}
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($loan->status === 'dipinjam')
                                            <button wire:click="returnBook({{ $loan->id }})"
                                                class="btn btn-xs btn-primary">
                                                Kembalikan
                                            </button>
                                        @elseif($loan->status === 'dikembalikan' && !$loan->hasRating)
                                            <button wire:click="showRatingForm({{ $loan->id }})"
                                                class="btn btn-xs btn-secondary">
                                                Beri Rating
                                            </button>
                                        @elseif($loan->bukti_pengiriman)
                                            <button wire:click="showProof({{ $loan->id }})"
                                                class="btn btn-xs btn-outline">
                                                Lihat Bukti
                                            </button>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        Tidak ada data peminjaman
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $loans->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Bukti Pengiriman -->
    @if ($selectedLoan && $showingProof)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-4 max-w-lg w-full mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Bukti Pengiriman</h3>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex justify-center">
                    <img src="{{ Storage::url($selectedLoan->bukti_pengiriman) }}" alt="Bukti Pengiriman"
                        class="max-h-96 object-contain">
                </div>
                <div class="mt-4 text-center">
                    <button wire:click="closeModal" class="btn btn-primary">Tutup</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal untuk Form Rating -->
    @if ($selectedLoan && $showingRatingForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-4 max-w-lg w-full mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Beri Rating untuk "{{ $selectedLoan->buku->judul }}"</h3>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="submitRating">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-2">Rating</label>
                        <div class="flex space-x-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="setRating({{ $i }})" class="text-2xl">
                                    @if ($i <= $rating)
                                        <span class="text-yellow-400">★</span>
                                    @else
                                        <span class="text-gray-300">★</span>
                                    @endif
                                </button>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="komentar" class="block text-sm font-medium mb-2">Komentar</label>
                        <textarea wire:model="komentar" id="komentar" rows="4" class="textarea textarea-bordered w-full"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="foto" class="block text-sm font-medium mb-2">Foto Review (Opsional)</label>
                        <input type="file" wire:model="fotoReview" id="foto"
                            class="file-input file-input-bordered w-full" />
                        @error('fotoReview')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror

                        @if ($fotoReview)
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Preview:</p>
                                <img src="{{ $fotoReview->temporaryUrl() }}" class="mt-1 h-32 object-cover">
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" wire:click="closeModal" class="btn btn-outline">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim Rating</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Konfirmasi Modal -->
    @if ($showingConfirmation)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-4 max-w-md w-full mx-4">
                <div class="text-center mb-4">
                    <h3 class="text-lg font-semibold">Konfirmasi Pengembalian</h3>
                    <p class="mt-2">Apakah Anda yakin ingin mengembalikan buku
                        "{{ $selectedLoan ? $selectedLoan->buku->judul : '' }}"?</p>
                </div>

                <div class="flex justify-center space-x-4 mt-4">
                    <button wire:click="closeModal" 
                            class="btn btn-outline"
                            wire:loading.attr="disabled">
                        Batal
                    </button>
                    <button wire:click="confirmReturn" 
                            class="btn btn-primary"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="confirmReturn">
                            Ya, Kembalikan
                        </span>
                        <span wire:loading wire:target="confirmReturn">
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Success Modal -->
    @if ($showingSuccess)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-4 max-w-md w-full mx-4">
                <div class="text-center mb-4">
                    <div class="text-4xl text-green-500 mb-2">✓</div>
                    <h3 class="text-lg font-semibold">{{ $successMessage }}</h3>
                </div>

                <div class="flex justify-center mt-4">
                    <button wire:click="closeModal" class="btn btn-primary">Tutup</button>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Tambahkan script untuk refresh halaman -->
<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('refreshPeminjaman', () => {
        // Refresh komponen setelah pengembalian berhasil
        Livewire.dispatch('$refresh');
    });
});
</script>
