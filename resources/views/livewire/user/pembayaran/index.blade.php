<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-semibold mb-6">Pembayaran Denda</h2>

                @if(session('message'))
                    <div class="alert alert-success mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('message') }}</span>
                    </div>
                @endif

                @if($loans->isEmpty())
                    <div class="text-center py-8">
                        <h3 class="text-lg font-medium text-gray-900">Tidak ada denda yang perlu dibayar</h3>
                        <p class="mt-1 text-sm text-gray-500">Anda dapat meminjam buku kembali</p>
                        <a href="{{ route('books') }}" class="btn btn-primary mt-4">Lihat Koleksi Buku</a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tenggat</th>
                                    <th>Denda</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loans as $loan)
                                    <tr>
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
                                        <td>{{ $loan->tgl_kembali_rencana->format('d/m/Y') }}</td>
                                        <td>Rp {{ number_format($loan->total_denda, 0, ',', '.') }}</td>
                                        <td>
                                            <button wire:click="initiatePayment({{ $loan->id }})" 
                                                    class="btn btn-sm btn-primary">
                                                Bayar & Kembalikan
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right font-bold">Total Denda:</td>
                                    <td colspan="2" class="font-bold">
                                        Rp {{ number_format($totalDenda, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Pembayaran -->
    @if($showPaymentModal && $selectedLoan)
    <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/70">
        <div class="bg-white rounded-xl w-11/12 max-w-md">
            <div class="p-6">
                <h3 class="text-lg font-bold mb-4">Pembayaran Denda</h3>
                
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Buku:</p>
                    <p class="font-medium">{{ $selectedLoan->buku->judul }}</p>
                </div>

                <div class="mb-6">
                    <p class="text-sm text-gray-600">Total Denda:</p>
                    <p class="text-xl font-bold">Rp {{ number_format($selectedLoan->total_denda, 0, ',', '.') }}</p>
                </div>

                <div class="mb-6">
                    <label class="label">
                        <span class="label-text">Metode Pembayaran</span>
                    </label>
                    <select wire:model="paymentMethod" class="select select-bordered w-full">
                        <option value="">Pilih metode pembayaran</option>
                        <option value="cash">Cash</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="ewallet">E-Wallet</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3">
                    <button class="btn btn-ghost" wire:click="$set('showPaymentModal', false)">
                        Batal
                    </button>
                    <button class="btn btn-primary" 
                            wire:click="processPayment"
                            wire:loading.attr="disabled"
                            {{ !$paymentMethod ? 'disabled' : '' }}>
                        <span wire:loading.remove>Bayar Sekarang</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div> 