<div>
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-semibold text-gray-800">Data Peminjaman</h2>
            </div>
            <div class="mt-4 flex flex-col sm:flex-row gap-4">
                <input type="text" wire:model.live="search" placeholder="Cari peminjaman..." 
                       class="input input-bordered w-full sm:w-auto" />
                
                <select wire:model.live="status" class="select select-bordered w-full sm:w-auto">
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

        <div class="p-4">
            <table class="w-full">
                <thead>
                    <tr class="text-left border-b border-gray-100">
                        <th class="pb-4 font-medium text-gray-400">Peminjam</th>
                        <th class="pb-4 font-medium text-gray-400 hidden md:table-cell">Buku</th>
                        <th class="pb-4 font-medium text-gray-400 hidden lg:table-cell">Tgl Pinjam</th>
                        <th class="pb-4 font-medium text-gray-400 hidden sm:table-cell">Tgl Kembali</th>
                        <th class="pb-4 font-medium text-gray-400">Status</th>
                        <th class="pb-4 font-medium text-gray-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($loans as $loan)
                    <tr>
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden">
                                    <img src="{{ $loan->user->profile_img ? Storage::url('profiles/' . $loan->user->profile_img) : 'https://ui-avatars.com/api/?name='.urlencode($loan->user->name).'&background=random' }}" 
                                         alt="{{ $loan->user->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <span class="font-medium">{{ $loan->user->name }}</span>
                                    <!-- Tampilkan info buku di mobile -->
                                    <span class="block text-sm text-gray-500 md:hidden">
                                        {{ Str::limit($loan->buku->judul, 30) }}
                                    </span>
                                    <!-- Tampilkan tanggal di mobile -->
                                    <span class="block text-xs text-gray-400 sm:hidden">
                                        {{ $loan->tgl_pinjam ? $loan->tgl_pinjam->format('d/m/Y') : '-' }} -
                                        {{ $loan->tgl_kembali_rencana ? $loan->tgl_kembali_rencana->format('d/m/Y') : '-' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 hidden md:table-cell">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-14 rounded-lg overflow-hidden bg-gray-100">
                                    @if($loan->buku->cover_img)
                                        <img src="{{ Storage::url($loan->buku->cover_img) }}" 
                                             alt="{{ $loan->buku->judul }}"
                                             class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <span class="font-medium">{{ Str::limit($loan->buku->judul, 30) }}</span>
                            </div>
                        </td>
                        <td class="py-4 hidden lg:table-cell">
                            {{ $loan->tgl_pinjam ? $loan->tgl_pinjam->format('d/m/Y') : '-' }}
                        </td>
                        <td class="py-4 hidden sm:table-cell">
                            {{ $loan->tgl_kembali_rencana ? $loan->tgl_kembali_rencana->format('d/m/Y') : '-' }}
                        </td>
                        <td class="py-4">
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
                        <td class="py-4">
                            <div class="flex gap-2">
                                <button wire:click="showDetail({{ $loan->id }})" class="btn btn-sm btn-ghost">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <button wire:click="updateStatus({{ $loan->id }})" class="btn btn-sm btn-ghost">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
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
            {{ $loans->links() }}
        </div>
    </div>

    <!-- Modal detail dan update status tetap sama -->
</div>
