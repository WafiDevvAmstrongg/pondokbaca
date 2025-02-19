<div>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Loan Management</h2>
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
                            <th>User</th>
                            <th>Book</th>
                            <th>Status</th>
                            <th>Loan Date</th>
                            <th>Return Date</th>
                            <th>Fine</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg overflow-hidden">
                                        <img src="{{ $loan->user->profile_img ?? 'https://ui-avatars.com/api/?name='.$loan->user->name }}" 
                                             alt="{{ $loan->user->name }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm">{{ $loan->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $loan->user->email }}</p>
                                    </div>
                                </div>
                            </td>
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
                            <td>
                                <div class="flex gap-2">
                                    <button class="btn btn-sm btn-ghost" title="View Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    @if($loan->status === 'pending')
                                        <button class="btn btn-sm btn-ghost text-success" title="Accept">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                        <button class="btn btn-sm btn-ghost text-error" title="Reject">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
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