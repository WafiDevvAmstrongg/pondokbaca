<?php

// 📌 Namespace untuk menentukan lokasi class ini dalam struktur Livewire User Peminjamans
namespace App\Livewire\User\Peminjamans;

// 📌 Mengimpor model yang dibutuhkan untuk peminjaman dan rating buku
use App\Models\Peminjaman;
use App\Models\Rating;

// 📌 Mengimpor Livewire Component untuk membuat komponen dinamis
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

// 📌 Mengimpor Storage untuk menyimpan foto review
use Illuminate\Support\Facades\Storage;

// 📌 Mengimpor DB untuk query database yang kompleks
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    // 📌 Menggunakan fitur paginasi dan upload file dari Livewire
    use WithPagination, WithFileUploads;

    // 📌 Variabel untuk filter pencarian dan status peminjaman
    public $status = ''; // Status peminjaman (dipinjam, terlambat, dikembalikan)
    public $search = ''; // Kata kunci pencarian buku berdasarkan judul
    public $selectedLoan = null; // Data peminjaman yang sedang dipilih
    public $showingProof = false; // Status modal bukti peminjaman
    public $showingRatingForm = false; // Status modal rating buku
    public $showingConfirmation = false; // Status modal konfirmasi pengembalian buku
    public $showingSuccess = false; // Status modal sukses
    public $successMessage = ''; // Pesan sukses setelah pengembalian atau rating

    // 📌 Variabel untuk rating buku
    public $rating = 0;
    public $komentar = '';
    public $fotoReview = null;

    // 📌 Aturan validasi untuk rating buku
    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'komentar' => 'required|string|min:5',
        'fotoReview' => 'nullable|image|max:2048', // Maksimum ukuran 2MB
    ];

    /**
     * 📌 RESET HALAMAN PAGINASI SAAT PENCARIAN BERUBAH
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * 📌 RESET HALAMAN PAGINASI SAAT STATUS DIPILIH
     */
    public function updatingStatus()
    {
        $this->resetPage();
    }

    /**
     * 📌 MENAMPILKAN BUKTI PEMINJAMAN
     */
    public function showProof($loanId)
    {
        $this->selectedLoan = Peminjaman::find($loanId);
        $this->showingProof = true;
    }

    /**
     * 📌 MENAMPILKAN MODAL KONFIRMASI PENGEMBALIAN
     */
    public function returnBook($loanId)
    {
        $this->selectedLoan = Peminjaman::find($loanId);
        $this->showingConfirmation = true;
    }

    /**
     * 📌 MENGONFIRMASI PENGEMBALIAN BUKU
     * - Mengubah status peminjaman menjadi "dikembalikan".
     * - Menghapus denda jika ada.
     * - Menampilkan notifikasi sukses.
     */
    public function confirmReturn()
    {
        Peminjaman::where('id', $this->selectedLoan->id)->update([
            'status' => 'dikembalikan',
            'tgl_kembali_aktual' => now()
        ]);

        // 🔹 Menutup modal konfirmasi dan menampilkan modal sukses
        $this->showingConfirmation = false;
        $this->successMessage = 'Buku berhasil dikembalikan!';
        $this->showingSuccess = true;

        // 🔹 Memuat ulang komponen untuk memperbarui data
        $this->render();
    }

    /**
     * 📌 MENYEGARKAN DAFTAR PEMINJAMAN
     */
    public function refreshPeminjaman()
    {
        $this->render();
    }

    /**
     * 📌 MENAMPILKAN FORM RATING
     * - Cek apakah pengguna sudah memberikan rating untuk buku yang dipinjam.
     * - Jika belum, tampilkan form rating.
     */
    public function showRatingForm($loanId)
    {
        $this->selectedLoan = Peminjaman::find($loanId);

        // 🔹 Cek apakah pengguna sudah memberikan rating
        $existingRating = Rating::where('id_user', auth()->id())
            ->where('id_buku', $this->selectedLoan->id_buku)
            ->first();

        if (!$existingRating) {
            $this->reset(['rating', 'komentar', 'fotoReview']);
            $this->showingRatingForm = true;
        } else {
            $this->successMessage = 'Anda sudah memberikan rating untuk buku ini sebelumnya.';
            $this->showingSuccess = true;
        }
    }

    /**
     * 📌 MENYIMPAN NILAI RATING YANG DIPILIH
     */
    public function setRating($value)
    {
        $this->rating = $value;
    }

    /**
     * 📌 MENGIRIMKAN RATING DAN REVIEW
     * - Memastikan pengguna belum memberikan rating sebelumnya.
     * - Menyimpan rating, komentar, dan foto review jika ada.
     * - Menampilkan pesan sukses setelah rating dikirim.
     */
    public function submitRating()
    {
        $this->validate();

        if (!$this->selectedLoan) {
            return;
        }

        $data = [
            'id_user' => auth()->id(),
            'id_buku' => $this->selectedLoan->id_buku,
            'rating' => $this->rating,
            'komentar' => $this->komentar,
        ];

        // 🔹 Menyimpan foto review jika ada
        if ($this->fotoReview) {
            $path = $this->fotoReview->store('rating-photos', 'public');
            $data['foto_review'] = $path;
        }

        // 🔹 Membuat rating baru
        Rating::create($data);

        // 🔹 Menutup form rating dan menampilkan notifikasi sukses
        $this->showingRatingForm = false;
        $this->successMessage = 'Terima kasih atas feedback Anda!';
        $this->showingSuccess = true;
    }

    /**
     * 📌 MENUTUP SEMUA MODAL
     */
    public function closeModal()
    {
        $this->selectedLoan = null;
        $this->showingProof = false;
        $this->showingRatingForm = false;
        $this->showingConfirmation = false;
        $this->showingSuccess = false;
        $this->reset(['rating', 'komentar', 'fotoReview']);
    }

    /**
     * 📌 MENAMPILKAN DAFTAR PEMINJAMAN
     */
    public function render()
    {
        $loans = Peminjaman::with(['buku'])
            ->where('id_user', auth()->id())
            ->when($this->search, function($query) {
                $query->whereHas('buku', function($q) {
                    $q->where('judul', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->status, function($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        // 🔹 Cek apakah buku sudah diberikan rating
        foreach ($loans as $loan) {
            $loan->hasRating = Rating::where('id_user', auth()->id())
                ->where('id_buku', $loan->id_buku)
                ->exists();
        }

        return view('livewire.user.peminjamans.index', [
            'loans' => $loans
        ])->layout('layouts.user');
    }
}
