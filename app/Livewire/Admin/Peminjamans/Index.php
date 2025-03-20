<?php
namespace App\Livewire\Admin\Peminjamans;

// 📌 Mengimpor model yang digunakan dalam sistem peminjaman
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;

// 📌 Mengimpor fitur Livewire untuk interaksi dinamis
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

// 📌 Mengimpor Str untuk manipulasi string (digunakan untuk nomor resi)
use Illuminate\Support\Str;

// 📌 Mengimpor Carbon untuk perhitungan tanggal (misalnya denda keterlambatan)
use Carbon\Carbon;

class Index extends Component
{
    // 📌 Menggunakan fitur paginasi dan upload file Livewire
    use WithPagination;
    use WithFileUploads;

    // 📌 Variabel untuk filter dan modal
    public $search = ''; // Kata kunci pencarian peminjam/buku
    public $status = ''; // Filter status peminjaman
    public $showRejectModal = false; // Modal untuk menolak peminjaman
    public $showShipmentModal = false; // Modal untuk konfirmasi pengiriman
    public $selectedLoanId = null; // ID peminjaman yang sedang dipilih
    public $alasanPenolakan = ''; // Input alasan penolakan peminjaman
    public $buktiPengiriman; // File bukti pengiriman
    public $nomorResi = ''; // Nomor resi pengiriman
    public $catatanPengiriman = ''; // Catatan tambahan untuk pengiriman

    /**
     * 📌 FUNGSI: Mereset halaman paginasi saat pencarian berubah.
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * 📌 FUNGSI: Menampilkan modal untuk menolak peminjaman.
     */
    public function openRejectModal($loanId)
    {
        $this->selectedLoanId = $loanId;
        $this->showRejectModal = true;
        $this->alasanPenolakan = '';
    }

    /**
     * FUNGSI: Menutup modal penolakan peminjaman.
     */
    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->selectedLoanId = null;
        $this->alasanPenolakan = '';
    }

    /**
     * 📌 FUNGSI: Menampilkan modal untuk konfirmasi pengiriman buku.
     */
    public function openShipmentModal($loanId)
    {
        $loan = Peminjaman::findOrFail($loanId);
        $this->selectedLoanId = $loanId;
        $this->nomorResi = $loan->nomor_resi;
        $this->showShipmentModal = true;
        $this->buktiPengiriman = null;
    }

    /**
     * 📌 FUNGSI: Menutup modal konfirmasi pengiriman.
     */
    public function closeShipmentModal()
    {
        $this->showShipmentModal = false;
        $this->selectedLoanId = null;
        $this->buktiPengiriman = null;
        $this->nomorResi = '';
    }

    /**
     * 📌 FUNGSI: Mengonfirmasi pengiriman buku yang dipinjam.
     */
    public function confirmShipment()
    {
        $this->validate([
            'buktiPengiriman' => 'required|image|max:2048', // File wajib berupa gambar max 2MB
        ]);

        $loan = Peminjaman::findOrFail($this->selectedLoanId);

        if ($loan->status === 'diproses') {
            // 📌 Simpan bukti pengiriman
            $path = $this->buktiPengiriman->store('bukti-pengiriman', 'public');

            $loan->update([
                'status' => 'dikirim',
                'bukti_pengiriman' => $path,
                'tgl_dikirim' => now()
            ]);

            session()->flash('message', 'Peminjaman berhasil dikonfirmasi pengirimannya.');
            $this->dispatch('$refresh');
        }

        $this->closeShipmentModal();
    }

    /**
     * 📌 FUNGSI: Menyetujui peminjaman buku.
     */
    public function approve($loanId)
    {
        $loan = Peminjaman::findOrFail($loanId);

        if ($loan->status === 'pending') {
            // 📌 Generate nomor resi unik
            $nomorResi = 'PJM-' . strtoupper(Str::random(8)) . '-' . date('Ymd');

            $loan->update([
                'status' => 'diproses',
                'id_staff' => auth()->id(),
                'nomor_resi' => $nomorResi
            ]);

            session()->flash('message', 'Peminjaman berhasil disetujui.');
            $this->dispatch('$refresh');
        }
    }

    /**
     * 📌 FUNGSI: Menolak peminjaman buku dengan alasan tertentu.
     */
    public function reject()
    {
        $this->validate([
            'alasanPenolakan' => 'required|min:10'
        ]);

        $loan = Peminjaman::findOrFail($this->selectedLoanId);

        if ($loan->status === 'pending') {
            $loan->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $this->alasanPenolakan,
                'id_staff' => auth()->id()
            ]);

            session()->flash('message', 'Peminjaman telah ditolak.');
        }

        $this->closeRejectModal();
        $this->dispatch('$refresh');
    }

    /**
     * 📌 FUNGSI: Menandai buku sebagai "Dipinjam" setelah diterima oleh peminjam.
     */
    public function markAsReceived($loanId)
    {
        $loan = Peminjaman::findOrFail($loanId);

        if ($loan->status === 'dikirim') {
            $loan->update([
                'status' => 'dipinjam',
                'tgl_peminjaman' => now()
            ]);

            session()->flash('message', 'Status peminjaman berhasil diupdate ke Dipinjam.');
            $this->dispatch('$refresh');
        }
    }

    /**
     * 📌 FUNGSI: Menampilkan data peminjaman dalam bentuk tabel dengan filter pencarian dan status.
     */
    public function render()
    {
        $totalUsers = User::count();
        $totalBooks = Buku::count();
        $totalLoans = Peminjaman::count();
        $activeLoans = Peminjaman::whereIn('status', ['diproses', 'dikirim', 'dipinjam'])->count();

        $loans = Peminjaman::with(['user', 'buku'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('buku', function ($q) {
                    $q->where('judul', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        // 📌 Perhitungan denda untuk peminjam yang terlambat mengembalikan buku
        foreach ($loans as $loan) {
            if (in_array($loan->status, ['dipinjam', 'terlambat']) && $loan->tgl_kembali_rencana) {
                $today = Carbon::now();
                $dueDate = Carbon::parse($loan->tgl_kembali_rencana);

                if ($today->greaterThan($dueDate)) {
                    $daysLate = $today->diffInDays($dueDate);
                    $totalDenda = $daysLate * $loan->buku->denda_harian;

                    if ($loan->total_denda !== $totalDenda) {
                        $loan->update([
                            'status' => 'terlambat',
                            'total_denda' => $totalDenda
                        ]);
                    }
                }
            }
        }

        return view('livewire.admin.peminjamans.index', compact(
            'totalUsers', 'totalBooks', 'totalLoans', 'activeLoans', 'loans'
        ))->layout('layouts.admin', ['title' => 'Admin Dashboard']);
    }
}
