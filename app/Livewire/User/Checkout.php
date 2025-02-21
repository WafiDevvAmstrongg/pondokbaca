<?php

namespace App\Livewire\User;

use App\Models\Buku;
use App\Models\Peminjaman;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Checkout extends Component
{
    public $token;
    public $book;
    public $alamat_pengiriman;
    public $catatan_pengiriman;
    public $tgl_peminjaman_diinginkan;

    public function mount($token)
    {
        // Validasi token
        $checkout = session('checkout_token');
        $expires = session('checkout_expires_at');
        
        if (!$checkout || $checkout !== $token || now()->gt($expires)) {
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Link checkout sudah tidak valid atau kadaluarsa. Silakan ulangi proses peminjaman.'
            ]);
            return redirect()->route('home');
        }

        $this->token = $token;
        $this->book = Buku::find(session('checkout_book_id'));
        
        if (!$this->book) {
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Buku tidak ditemukan'
            ]);
            return redirect()->route('home');
        }

        // Pre-fill alamat dari data user
        $this->alamat_pengiriman = auth()->user()->alamat;
    }

    public function checkout()
    {
        $this->validate([
            'alamat_pengiriman' => 'required|string',
            'tgl_peminjaman_diinginkan' => 'required|date|after:today'
        ]);

        try {
            DB::beginTransaction();

            // Buat peminjaman
            Peminjaman::create([
                'id_user' => auth()->id(),
                'id_buku' => $this->book->id,
                'alamat_pengiriman' => $this->alamat_pengiriman,
                'catatan_pengiriman' => $this->catatan_pengiriman,
                'tgl_peminjaman_diinginkan' => $this->tgl_peminjaman_diinginkan,
                'status' => 'pending'
            ]);

            // Kurangi stok buku
            $this->book->decrement('stok');

            // Hapus data checkout dari session
            session()->forget(['checkout_token', 'checkout_book_id', 'checkout_expires_at']);

            DB::commit();

            $this->dispatch('showAlert', [
                'type' => 'success',
                'message' => 'Peminjaman buku berhasil diajukan! Kami akan segera memproses permintaan Anda.'
            ]);

            return redirect()->route('my-loans');

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Maaf, terjadi kesalahan saat memproses peminjaman. Silakan coba lagi.'
            ]);

            return redirect()->route('home');
        }
    }

    public function render()
    {
        return view('livewire.user.checkout')->layout('layouts.user');
    }
} 