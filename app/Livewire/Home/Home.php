<?php

// 📌 Namespace untuk menentukan lokasi class ini dalam struktur Livewire Home
namespace App\Livewire\Home;

// 📌 Mengimpor model Buku untuk mengambil data buku dari database
use App\Models\Buku;

// 📌 Mengimpor DB untuk melakukan perhitungan khusus pada query SQL
use Illuminate\Support\Facades\DB;

// 📌 Mengimpor fitur Livewire untuk membuat komponen dinamis
use Livewire\Component;

class Home extends Component
{
    // 📌 Listener untuk menangani event refresh buku
    protected $listeners = ['refresh-books' => '$refresh'];

    /**
     * 📌 FUNGSI RENDER
     * - Mengambil daftar buku populer berdasarkan jumlah "suka".
     * - Mengambil daftar buku dengan rating tertinggi menggunakan formula Wilson Score.
     * - Mengirimkan data ini ke tampilan Home.
     */
    public function render()
    {
        // 📌 Mengambil 5 buku yang paling banyak disukai oleh pengguna
        $favoriteBooks = Buku::select([
                'bukus.id', 
                'bukus.judul', 
                'bukus.penulis', 
                'bukus.cover_img', 
                'bukus.deskripsi', 
                'bukus.stok',
                'bukus.isbn',
                'bukus.penerbit',
                'bukus.tahun_terbit',
                'bukus.kategori',
                'bukus.denda_harian'
            ])
            ->with(['suka.user', 'ratings.user']) // 📌 Memuat relasi pengguna yang menyukai & memberi rating
            ->withCount('suka') // 📌 Menghitung jumlah "suka" untuk setiap buku
            ->withAvg('ratings', 'rating') // 📌 Mengambil rata-rata rating buku
            ->orderByDesc('suka_count') // 📌 Mengurutkan berdasarkan jumlah suka terbanyak
            ->take(5) // 📌 Hanya mengambil 5 buku teratas
            ->get();
        
        // 📌 Mengambil 5 buku dengan rating tertinggi menggunakan Wilson Score formula
        $topRatedBooks = Buku::select([
                'bukus.id',
                'bukus.judul',
                'bukus.penulis',
                'bukus.cover_img',
                'bukus.deskripsi',
                'bukus.stok',
                'bukus.isbn',
                'bukus.penerbit',
                'bukus.tahun_terbit',
                'bukus.kategori',
                'bukus.denda_harian'
            ])
            ->with(['suka.user', 'ratings.user']) // 📌 Memuat relasi suka dan rating
            ->withCount('suka') // 📌 Menghitung jumlah suka
            ->withAvg('ratings', 'rating') // 📌 Mengambil rata-rata rating
            ->leftJoin('ratings', 'bukus.id', '=', 'ratings.id_buku')
            ->leftJoin('peminjamans', 'bukus.id', '=', 'peminjamans.id_buku')
            ->select([
                'bukus.*',
                DB::raw('COUNT(DISTINCT ratings.id) as total_ratings'), // 📌 Menghitung jumlah rating unik
                DB::raw('AVG(ratings.rating) as ratings_avg_rating'), // 📌 Mengambil rata-rata rating
                DB::raw('COUNT(DISTINCT peminjamans.id) as borrow_count'), // 📌 Menghitung jumlah peminjaman
                // 📌 Rumus yang menggabungkan rating dan jumlah peminjaman
                DB::raw('(AVG(ratings.rating) * COUNT(DISTINCT ratings.id) / (COUNT(DISTINCT ratings.id) + 10) + 
                         (COUNT(DISTINCT peminjamans.id) / 100)) as adjusted_score')
            ])
            ->groupBy([
                'bukus.id',
                'bukus.judul',
                'bukus.penulis',
                'bukus.cover_img',
                'bukus.deskripsi',
                'bukus.stok',
                'bukus.isbn',
                'bukus.penerbit',
                'bukus.tahun_terbit',
                'bukus.kategori',
                'bukus.denda_harian',
                'bukus.created_at',
                'bukus.updated_at'
            ])
            ->having('total_ratings', '>', 0) // 📌 Hanya menampilkan buku dengan rating lebih dari 0
            ->orderByDesc('adjusted_score') // 📌 Mengurutkan berdasarkan skor yang disesuaikan
            ->take(5) // 📌 Hanya mengambil 5 buku teratas
            ->get();
        
        // 📌 Menandai apakah pengguna saat ini telah menyukai buku tertentu
        if (auth()->check()) {
            $userId = auth()->id();
            $favoriteBooks->each(function($book) use ($userId) {
                $book->isSukaBy = function($id) use ($book, $userId) {
                    return $book->suka->contains('id_user', $userId);
                };
            });
            
            $topRatedBooks->each(function($book) use ($userId) {
                $book->isSukaBy = function($id) use ($book, $userId) {
                    return $book->suka->contains('id_user', $userId);
                };
            });
        }
        
        // 📌 Daftar kategori buku yang tersedia di sistem
        $categories = [
            'al-quran', 'hadis', 'fikih', 'akidah', 'sirah', 
            'tafsir', 'tarbiyah', 'sejarah', 'buku-anak', 'novel'
        ];

        return view('livewire.home.home', [
            'favoriteBooks' => $favoriteBooks, // 📌 Mengirim daftar buku populer
            'topRatedBooks' => $topRatedBooks, // 📌 Mengirim daftar buku dengan rating tertinggi
            'categories' => $categories // 📌 Mengirim daftar kategori
        ])->layout('layouts.user', [
            'title' => 'Home - PondokBaca' // 📌 Mengatur judul halaman
        ]);
    }
}
