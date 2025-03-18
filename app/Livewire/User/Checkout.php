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
    public $alamat_pengiriman;
    public $catatan_pengiriman;
    public $tgl_peminjaman_diinginkan;
    public $tgl_kembali_rencana;
    public $maxReturnDate;
    public $minReturnDate;

    protected function rules()
    {
        return [
            'tgl_peminjaman_diinginkan' => 'required|date|after_or_equal:today',
            'tgl_kembali_rencana' => [
                'required',
                'date',
                'after:tgl_peminjaman_diinginkan',
                'before_or_equal:maxReturnDate'
            ],
            'alamat_pengiriman' => 'required|string|min:10',
            'catatan_pengiriman' => 'nullable|string'
        ];
    }

    protected $messages = [
        'tgl_peminjaman_diinginkan.after_or_equal' => 'Tanggal peminjaman tidak boleh kurang dari hari ini',
        'tgl_kembali_rencana.after' => 'Minimal peminjaman adalah 1 hari',
        'tgl_kembali_rencana.before_or_equal' => 'Maksimal peminjaman adalah 7 hari',
        'alamat_pengiriman.required' => 'Alamat pengiriman harus diisi',
        'alamat_pengiriman.min' => 'Alamat pengiriman terlalu pendek'
    ];

    public function updatedTglPeminjamanDiinginkan($value)
    {
        if ($value) {
            $this->maxReturnDate = Carbon::parse($value)->addDays(7)->format('Y-m-d');
            $this->minReturnDate = Carbon::parse($value)->addDay()->format('Y-m-d');
            
            // Reset tanggal pengembalian jika sudah tidak valid
            if ($this->tgl_kembali_rencana) {
                $tglKembali = Carbon::parse($this->tgl_kembali_rencana);
                if ($tglKembali->lte($value) || $tglKembali->gt($this->maxReturnDate)) {
                    $this->tgl_kembali_rencana = null;
                }
            }
        }
    }

    public function mount($token)
    {
        // Validasi token
        $checkout = session('checkout_token');
        $expires = session('checkout_expires_at');
        
        if (!$checkout || $checkout !== $token || now()->gt($expires)) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Link checkout tidak valid atau sudah kadaluarsa.',
                'icon' => 'error'
            ]);
            return redirect()->route('home');
        }

        $this->token = $token;
        $this->book = Buku::find(session('checkout_book_id'));
        
        if (!$this->book) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Buku tidak ditemukan.',
                'icon' => 'error'
            ]);
            return redirect()->route('home');
        }

        // Pre-fill alamat dari data user
        $this->alamat_pengiriman = auth()->user()->alamat;
    }

    public function checkout()
    {
        // Cek apakah user memiliki peminjaman terlambat/denda
        $hasPendingFines = Peminjaman::where('id_user', auth()->id())
            ->where(function($query) {
                $query->where('status', 'terlambat')
                    ->orWhere('total_denda', '>', 0);
            })->exists();

        if ($hasPendingFines) {
            $this->dispatch('swal', [
                'title' => 'Tidak dapat meminjam!',
                'text' => 'Anda memiliki denda yang belum dibayar.',
                'icon' => 'error'
            ]);
            return;
        }

        $this->validate();

        try {
            DB::beginTransaction();

            Peminjaman::create([
                'id_user' => auth()->id(),
                'id_buku' => $this->book->id,
                'alamat_pengiriman' => $this->alamat_pengiriman,
                'catatan_pengiriman' => $this->catatan_pengiriman,
                'tgl_peminjaman_diinginkan' => $this->tgl_peminjaman_diinginkan,
                'tgl_kembali_rencana' => $this->tgl_kembali_rencana,
                'status' => 'pending'
            ]);


            // Hapus data checkout dari session
            session()->forget(['checkout_token', 'checkout_book_id', 'checkout_expires_at']);

            DB::commit();

            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Peminjaman buku berhasil diajukan.',
                'icon' => 'success'
            ]);

            return redirect()->route('home');

        } catch (\Exception $e) {
            DB::rollBack();
            
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan saat memproses peminjaman.',
                'icon' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.user.checkout')->layout('layouts.user');
    }
} 