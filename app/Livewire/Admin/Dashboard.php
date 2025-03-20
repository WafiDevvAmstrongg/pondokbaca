<?php

// 📌 Namespace untuk menentukan lokasi class ini dalam struktur Livewire Admin
namespace App\Livewire\Admin;

// 📌 Mengimpor model yang dibutuhkan untuk mengambil data statistik
use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;

// 📌 Mengimpor Livewire Component untuk membuat komponen dashboard dinamis
use Livewire\Component;

class Dashboard extends Component
{
    /**
     * 📌 FUNGSI RENDER
     * Fungsi ini mengambil data statistik dan peminjaman terbaru untuk ditampilkan di dashboard admin.
     */
    public function render()
    {
        // 📌 Menghitung jumlah total pengguna yang terdaftar di sistem
        $totalUsers = User::count();

        // 📌 Menghitung jumlah total buku yang tersedia dalam sistem
        $totalBooks = Buku::count();

        // 📌 Menghitung jumlah total transaksi peminjaman buku
        $totalLoans = Peminjaman::count();

        // 📌 Menghitung jumlah peminjaman yang sedang aktif (masih dalam proses peminjaman)
        $activeLoans = Peminjaman::whereIn('status', ['diproses', 'dikirim', 'dipinjam'])->count();

        // 📌 Mengambil 5 transaksi peminjaman terbaru untuk ditampilkan di dashboard
        $recentLoans = Peminjaman::with(['user', 'buku']) // Mengambil relasi user dan buku
                                 ->latest() // Urutkan dari yang terbaru
                                 ->take(5) // Ambil hanya 5 transaksi terakhir
                                 ->get();

        // 📌 Mengambil 10 peminjaman terbaru untuk keperluan lain di dashboard
        $loans = Peminjaman::with(['user', 'buku']) // Mengambil relasi user dan buku
                           ->latest() // Urutkan dari yang terbaru
                           ->take(10) // Ambil hanya 10 transaksi terakhir
                           ->get();

        // 📌 Mengirim data ke tampilan Livewire
        return view('livewire.admin.dashboard', [
            'totalUsers' => $totalUsers, // Kirim total pengguna ke view
            'totalBooks' => $totalBooks, // Kirim total buku ke view
            'totalLoans' => $totalLoans, // Kirim total peminjaman ke view
            'activeLoans' => $activeLoans, // Kirim jumlah peminjaman aktif ke view
            'recentLoans' => $recentLoans, // Kirim daftar peminjaman terbaru ke view
            'loans' => $loans // Kirim daftar 10 peminjaman terbaru ke view
        ])->layout('layouts.admin', [
            'title' => 'Admin Dashboard' // Mengatur judul halaman di layout admin
        ]);
    }
}
