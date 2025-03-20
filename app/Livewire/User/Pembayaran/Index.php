<?php

// 📌 Namespace untuk menentukan lokasi class ini dalam struktur Livewire User Pembayaran
namespace App\Livewire\User\Pembayaran;

// 📌 Mengimpor model Peminjaman untuk mengambil data peminjaman dan denda
use App\Models\Peminjaman;

// 📌 Mengimpor Livewire Component untuk membuat komponen pembayaran yang dinamis
use Livewire\Component;

// 📌 Mengimpor Carbon untuk menangani tanggal pengembalian buku
use Carbon\Carbon;

class Index extends Component
{
    // 📌 Variabel untuk mengontrol tampilan modal dan proses pembayaran
    public $showPaymentModal = false; // Status modal pembayaran
    public $selectedLoan = null; // Peminjaman yang dipilih untuk dibayar
    public $paymentMethod = ''; // Metode pembayaran yang dipilih
    public $processingPayment = false; // Status pemrosesan pembayaran

    /**
     * 📌 FUNGSI MOUNT
     * - Digunakan saat komponen pertama kali diinisialisasi.
     * - Menghapus sesi pembayaran sebelumnya agar tidak ada status lama yang tertinggal.
     */
    public function mount()
    {
        session()->forget('payment_success'); // 🔹 Menghapus sesi pembayaran sebelumnya
    }

    /**
     * 📌 FUNGSI MEMULAI PEMBAYARAN
     * - Mengambil data peminjaman yang memiliki denda atau keterlambatan.
     * - Menampilkan modal pembayaran.
     */
    public function initiatePayment($loanId)
    {
        $this->selectedLoan = Peminjaman::findOrFail($loanId); // 🔹 Mengambil data peminjaman berdasarkan ID
        $this->showPaymentModal = true; // 🔹 Menampilkan modal pembayaran
    }

    /**
     * 📌 FUNGSI MEMPROSES PEMBAYARAN
     * - Simulasi proses pembayaran dengan delay (sleep).
     * - Setelah pembayaran sukses, peminjaman diperbarui menjadi "dikembalikan".
     */
    public function processPayment()
    {
        $this->processingPayment = true; // 🔹 Menandai proses pembayaran sedang berjalan

        // 🔹 Simulasi pemrosesan pembayaran selama 2 detik
        sleep(2);

        // 🔹 Memperbarui status peminjaman setelah pembayaran sukses
        $this->selectedLoan->update([
            'status' => 'dikembalikan', // 🔹 Status diubah menjadi "dikembalikan"
            'total_denda' => 0, // 🔹 Denda dihapus karena sudah dibayar
            'tgl_kembali_aktual' => now() // 🔹 Mencatat tanggal pengembalian aktual
        ]);

        // 🔹 Menampilkan notifikasi sukses pembayaran
        session()->flash('message', 'Pembayaran berhasil! Buku telah dikembalikan.');
        
        // 🔹 Menutup modal pembayaran dan mereset status pemrosesan
        $this->showPaymentModal = false;
        $this->processingPayment = false;

        // 🔹 Mengirimkan event ke komponen lain bahwa pembayaran sukses
        $this->dispatch('payment-success');
    }

    /**
     * 📌 FUNGSI RENDER
     * - Mengambil daftar peminjaman yang memiliki denda atau status "terlambat".
     * - Menghitung total denda pengguna.
     */
    public function render()
    {
        // 🔹 Mengambil daftar peminjaman dengan status "terlambat" atau memiliki denda
        $loans = Peminjaman::where('id_user', auth()->id())
            ->where(function($query) {
                $query->where('status', 'terlambat')
                      ->orWhere('total_denda', '>', 0);
            })
            ->with('buku') // 🔹 Memuat relasi dengan tabel buku
            ->latest() // 🔹 Mengurutkan dari yang terbaru
            ->get();

        // 🔹 Menghitung total denda yang harus dibayar oleh pengguna
        $totalDenda = $loans->sum('total_denda');

        // 🔹 Mengembalikan data ke tampilan Livewire
        return view('livewire.user.pembayaran.index', [
            'loans' => $loans, // 🔹 Mengirim daftar peminjaman yang harus dibayar
            'totalDenda' => $totalDenda // 🔹 Mengirim total denda pengguna
        ])->layout('layouts.user'); // 🔹 Menggunakan layout user untuk tampilan halaman
    }
}
