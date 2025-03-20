<?php

// 📌 Namespace untuk menentukan lokasi class ini dalam struktur Livewire User Favorit
namespace App\Livewire\User\Favorit;

// 📌 Mengimpor Livewire Component untuk membuat komponen daftar buku favorit
use Livewire\Component;

// 📌 Mengimpor model yang dibutuhkan
use App\Models\Suka; // Model untuk menyimpan buku yang disukai pengguna
use App\Models\Buku; // Model untuk mengambil data buku

// 📌 Mengimpor fitur paginasi Livewire untuk menampilkan daftar buku dalam beberapa halaman
use Livewire\WithPagination;

// 📌 Mengimpor Auth untuk mendapatkan ID pengguna yang sedang login
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    // 📌 Menggunakan fitur paginasi dari Livewire
    use WithPagination;
    
    // 📌 Event listener yang akan menangani event 'favoriteToggled' untuk menyegarkan daftar favorit
    protected $listeners = [
        'favoriteToggled' => '$refresh'
    ];
    
    /**
     * 📌 FUNGSI RENDER
     * - Mengambil daftar buku yang telah disukai oleh pengguna.
     * - Memuat data tambahan seperti jumlah suka dan rata-rata rating.
     * - Menampilkan data ini di halaman daftar buku favorit.
     */
    public function render()
    {
        // 📌 Mengambil ID buku yang telah disukai oleh pengguna yang sedang login
        $favoriteBookIds = Suka::where('id_user', Auth::id())
            ->pluck('id_buku'); // 🔹 Hanya mengambil kolom 'id_buku' dari tabel 'suka'
            
        // 📌 Mengambil daftar buku yang ada di daftar favorit pengguna
        $favoriteBooks = Buku::whereIn('id', $favoriteBookIds)
            ->with([
                'ratings.user', // 🔹 Memuat data user yang memberikan rating
                'suka.user' // 🔹 Memuat data user yang menyukai buku
            ])
            ->withCount('suka') // 🔹 Menghitung jumlah suka untuk setiap buku
            ->withAvg('ratings', 'rating') // 🔹 Mengambil rata-rata rating setiap buku
            ->latest() // 🔹 Mengurutkan buku dari yang terbaru
            ->paginate(12); // 🔹 Menggunakan paginasi dengan 12 buku per halaman
            
        // 📌 Mengembalikan data ke tampilan Livewire
        return view('livewire.user.favorit.index', [
            'favoriteBooks' => $favoriteBooks // 🔹 Mengirim daftar buku favorit ke tampilan
        ])->layout('layouts.user'); // 🔹 Menggunakan layout user untuk tampilan halaman
    }
}
