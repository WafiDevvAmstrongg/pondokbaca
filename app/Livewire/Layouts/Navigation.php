<?php

// 📌 Namespace untuk menentukan lokasi class ini dalam struktur Livewire Layouts
namespace App\Livewire\Layouts;

// 📌 Mengimpor model Buku untuk menangani pencarian buku di navigasi
use App\Models\Buku;

// 📌 Mengimpor Livewire Component untuk membuat komponen navigasi yang dinamis
use Livewire\Component;

// 📌 Mengimpor Auth untuk menangani logout pengguna
use Illuminate\Support\Facades\Auth;

class Navigation extends Component
{
    // 📌 Variabel untuk menyimpan input pencarian
    public $search = ''; // Kata kunci pencarian
    public $searchResults = []; // Hasil pencarian
    public $showDropdown = false; // Status tampilan dropdown hasil pencarian
    public $isSearching = false; // Status apakah pencarian sedang dilakukan

    // 📌 Mendengarkan event dari komponen lain
    protected $listeners = ['closeDetailModal' => 'resetSearch'];

    /**
     * 📌 FUNGSI MENJALANKAN PENCARIAN SAAT INPUT BERUBAH
     * - Jika input pencarian kurang dari 2 karakter, reset hasil pencarian.
     * - Jika sedang berada di halaman daftar buku, kirim event pencarian ke komponen terkait.
     * - Mengambil hasil pencarian berdasarkan judul atau penulis buku.
     */
    public function updatedSearch()
    {
        // Jika pencarian kurang dari 2 karakter, sembunyikan hasil pencarian
        if (strlen($this->search) < 2) {
            $this->searchResults = [];
            $this->showDropdown = false;
            return;
        }

        // Jika pencarian dilakukan di halaman daftar buku, kirim event ke komponen daftar buku
        if (request()->routeIs('books')) {
            $this->dispatch('search-updated', search: $this->search);
        }

        // 📌 Mulai pencarian
        $this->isSearching = true;

        // 📌 Ambil hasil pencarian (maksimal 5 hasil)
        $this->searchResults = Buku::where('judul', 'like', '%' . $this->search . '%')
            ->orWhere('penulis', 'like', '%' . $this->search . '%')
            ->take(5) // Batasi hanya 5 hasil pencarian
            ->get();

        // 📌 Tampilkan dropdown hasil pencarian
        $this->showDropdown = true;

        // 📌 Selesai mencari
        $this->isSearching = false;
    }

    /**
     * 📌 FUNGSI MENAMPILKAN DETAIL BUKU
     * - Mengirimkan event ke komponen modal detail buku.
     * - Mereset pencarian setelah buku dipilih.
     */
    public function showBookDetail($bookId)
    {
        $this->dispatch('showDetailModal', bookId: $bookId);
        $this->resetSearch();
    }

    /**
     * 📌 FUNGSI MERESERT PENCARIAN
     * - Membersihkan input pencarian dan hasil pencarian.
     */
    public function resetSearch()
    {
        $this->search = '';
        $this->searchResults = [];
        $this->showDropdown = false;
    }

    /**
     * 📌 FUNGSI MENUTUP DROPDOWN HASIL PENCARIAN
     * - Menyembunyikan hasil pencarian saat pengguna keluar dari input pencarian.
     */
    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    /**
     * 📌 FUNGSI LOGOUT
     * - Mengeluarkan pengguna dari sistem dan menghapus sesi.
     * - Mengarahkan pengguna kembali ke halaman utama ('/').
     */
    public function logout()
    {
        Auth::logout(); // Mengeluarkan pengguna dari sesi

        session()->invalidate(); // Menghapus semua sesi pengguna untuk keamanan

        session()->regenerateToken(); // Menghasilkan token CSRF baru agar lebih aman dari serangan CSRF

        return $this->redirect('/', navigate: true); // Mengarahkan pengguna kembali ke halaman utama
    }

    /**
     * 📌 MENAMPILKAN NAVIGASI
     * - Mengembalikan tampilan navigasi yang telah dibuat di view Blade.
     */
    public function render()
    {
        return view('livewire.layouts.navigation');
    }
}
