<?php

// 📌 Namespace untuk menentukan lokasi class ini dalam struktur Livewire Home Books
namespace App\Livewire\Home\Books;

// 📌 Mengimpor model Buku untuk mengambil data buku dari database
use App\Models\Buku;

// 📌 Mengimpor fitur Livewire untuk membuat komponen dinamis
use Livewire\Component;

// 📌 Mengimpor fitur paginasi untuk menampilkan daftar buku dalam beberapa halaman
use Livewire\WithPagination;

class Index extends Component
{
    // 📌 Menggunakan fitur paginasi dari Livewire
    use WithPagination;

    // 📌 Variabel untuk filter kategori dan pencarian buku
    public $selectedCategory = ''; // Kategori yang dipilih
    public $search = ''; // Kata kunci pencarian buku
    
    // 📌 Menangani event pencarian yang diperbarui dari komponen lain
    protected $listeners = ['search-updated' => 'updateSearch'];

    // 📌 Menyimpan kategori yang dipilih di URL agar tetap tersimpan saat berpindah halaman
    protected $queryString = ['selectedCategory'];

    /**
     * 📌 RESET HALAMAN PAGINASI SAAT KATEGORI BERUBAH
     * - Jika pengguna memilih kategori lain, daftar buku akan diperbarui dari halaman pertama.
     */
    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    /**
     * 📌 FUNGSI MEMPERBARUI PENCARIAN
     * - Digunakan oleh event `search-updated` dari komponen lain.
     */
    public function updateSearch($search)
    {
        $this->search = $search;
        $this->resetPage();
    }
    
    /**
     * 📌 FUNGSI MEMILIH KATEGORI
     * - Jika kategori yang dipilih sama dengan kategori yang sedang aktif, maka kategori akan dihapus (toggle).
     * - Jika berbeda, kategori baru akan dipilih.
     */
    public function selectCategory($category)
    {
        $this->selectedCategory = $this->selectedCategory === $category ? '' : $category;
        $this->resetPage();
    }

    /**
     * 📌 MENAMPILKAN DATA BUKU DENGAN PAGINASI
     * - Mengambil daftar buku berdasarkan kategori & pencarian.
     * - Memuat jumlah "suka" dan rata-rata rating untuk setiap buku.
     */
    public function render()
    {
        // 📌 Inisialisasi query untuk mengambil buku dengan informasi tambahan
        $query = Buku::query()
            ->select(['id', 'judul', 'penulis', 'cover_img', 'deskripsi', 'stok', 'kategori'])
            ->with('suka') // 📌 Memuat data suka (favorit) buku
            ->withAvg('ratings', 'rating') // 📌 Mengambil rata-rata rating
            ->withCount('suka'); // 📌 Menghitung jumlah pengguna yang menyukai buku

        // 📌 Jika kategori dipilih, filter berdasarkan kategori tersebut
        if ($this->selectedCategory) {
            $query->where('kategori', $this->selectedCategory);
        }

        // 📌 Jika ada pencarian, filter berdasarkan judul atau nama penulis
        if ($this->search) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('penulis', 'like', '%' . $this->search . '%');
            });
        }

        // 📌 Mengambil hasil query dengan paginasi (15 buku per halaman)
        $books = $query->paginate(15);

        return view('livewire.home.books.index', [
            'books' => $books, // 📌 Mengirim data buku ke tampilan
            'categories' => Buku::distinct('kategori')->pluck('kategori') // 📌 Mengambil daftar kategori unik
        ])->layout('layouts.user', [
            'title' => 'Daftar Buku - PondokBaca' // 📌 Mengatur judul halaman
        ]);
    }
}
