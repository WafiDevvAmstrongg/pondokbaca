<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-semibold mb-6">Peminjaman Saya</h2>

                <!-- Filter dan Search -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="form-control">
                        <div class="input-group">
                            <input type="text" 
                                   wire:model.live="search" 
                                   placeholder="Cari judul buku..." 
                                   class="input input-bordered w-full" />
                            <button class="btn btn-square">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <select wire:model.live="status" class="select select-bordered w-full">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="diproses">Diproses</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="dipinjam">Dipinjam</option>
                        <option value="terlambat">Terlambat</option>
                        <option value="selesai">Selesai</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>

                <!-- Daftar Peminjaman -->
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tenggat</th>
                                <th>Status</th>
                                <th>Info</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loans as $index => $loan)
                                <tr>
                                    <td>{{ $loans->firstItem() + $index }}</td>
                                    <td>
                                        <div class="flex items-center space-x-3">
                                            @if($loan->buku->cover_img)
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
                                    <td>{{ $loan->tgl_peminjaman ? $loan->tgl_peminjaman->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $loan->tgl_kembali_rencana ? $loan->tgl_kembali_rencana->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <div class="badge {{ 
                                            match($loan->status) {
                                                'pending' => 'badge-warning',
                                                'diproses' => 'badge-info',
                                                'dikirim' => 'badge-primary',
                                                'dipinjam' => 'badge-success',
                                                'terlambat' => 'badge-error',
                                                'selesai' => 'badge-success',
                                                'ditolak' => 'badge-error',
                                                default => 'badge-ghost'
                                            }
                                        }}">
                                            {{ ucfirst($loan->status) }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($loan->status === 'ditolak')
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
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
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
</div> 