<div>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">My Loans</h2>
            </div>
            <div class="mt-4 flex gap-4">
                <input type="text" wire:model.live="search" placeholder="Search loans..." 
                       class="input input-bordered w-full max-w-xs" />
                
                <select wire:model.live="status" class="select select-bordered">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="diproses">Diproses</option>
                    <option value="dikirim">Dikirim</option>
                    <option value="dipinjam">Dipinjam</option>
                    <option value="terlambat">Terlambat</option>
                    <option value="dikembalikan">Dikembalikan</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Status</th>
                            <th>Loan Date</th>
                            <th>Return Date</th>
                            <th>Fine</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-16 rounded-lg overflow-hidden">
                                        <img src="{{ $loan->buku->cover_img ? Storage::url($loan->buku->cover_img) : asset('images/default-book.jpg') }}" 
                                             alt="{{ $loan->buku->judul }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm">{{ $loan->buku->judul }}</p>
                                        <p class="text-xs text-gray-500">{{ $loan->buku->penulis }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ 
                                    match($loan->status) {
                                        'pending' => 'badge-warning',
                                        'diproses' => 'badge-info',
                                        'dikirim' => 'badge-primary',
                                        'dipinjam' => 'badge-success',
                                        'terlambat' => 'badge-error',
                                        'dikembalikan' => 'badge-neutral',
                                        'ditolak' => 'badge-error',
                                        default => 'badge-ghost'
                                    }
                                }}">
                                    {{ $loan->status }}
                                </span>
                            </td>
                            <td>{{ $loan->tgl_peminjaman_diinginkan ? $loan->tgl_peminjaman_diinginkan->format('d M Y') : '-' }}</td>
                            <td>{{ $loan->tgl_kembali_rencana ? $loan->tgl_kembali_rencana->format('d M Y') : '-' }}</td>
                            <td>
                                @if($loan->total_denda > 0)
                                    <span class="text-error font-medium">Rp {{ number_format($loan->total_denda, 0, ',', '.') }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $loans->links() }}
            </div>
        </div>
    </div>
</div> 