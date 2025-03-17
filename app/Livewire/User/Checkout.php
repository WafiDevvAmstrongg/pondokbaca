<?php

namespace App\Livewire\User;

use App\Models\Buku;
use App\Models\Peminjaman;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Checkout extends Component
{
    public $token;
    public $book;
    public $tglKembaliRencana;

    public function mount($token)
    {
        // Validasi token
        if (!session('checkout_token') || 
            session('checkout_token') !== $token || 
            now()->isAfter(session('checkout_expires_at'))) {
            return redirect()->route('books.index');
        }

        // Ambil buku dari session
        $this->book = Buku::find(session('checkout_book_id'));
        if (!$this->book) {
            return redirect()->route('books.index');
        }

        $this->token = $token;
    }

    public function checkout()
    {
        $this->validate([
            'tglKembaliRencana' => 'required|date|after:today|before_or_equal:' . now()->addDays(14)->format('Y-m-d')
        ]);

        // Cek stok sekali lagi
        if ($this->book->stok < 1) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Maaf, stok buku sudah tidak tersedia.',
                'icon' => 'error'
            ]);
            return redirect()->route('books.index');
        }

        // Buat peminjaman dengan status pending
        Peminjaman::create([
            'id_user' => auth()->id(),
            'id_buku' => $this->book->id,
            'tgl_peminjaman' => now(),
            'tgl_kembali_rencana' => $this->tglKembaliRencana,
            'status' => 'pending', // Status awal pending, tidak mengurangi stok
            'total_denda' => 0
        ]);

        // Hapus data checkout dari session
        session()->forget(['checkout_token', 'checkout_book_id', 'checkout_expires_at']);

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Permintaan peminjaman berhasil dibuat! Silahkan tunggu persetujuan admin.',
            'icon' => 'success'
        ]);

        return redirect()->route('user.peminjaman');
    }

    public function render()
    {
        return view('livewire.user.checkout');
    }
} 