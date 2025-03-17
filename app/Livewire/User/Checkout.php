<?php

namespace App\Livewire\User;

use App\Models\Buku;
use App\Models\Peminjaman;
use Livewire\Component;
use Carbon\Carbon;

class Checkout extends Component
{
    public $token;
    public $book;
    public $tglKembaliRencana;
    public $showDetailModal = false;
    public $selectedBook = null;
    public $checkoutToken = null;
    public $isSukaByUser = false;
    public $books = null;
    public $tgl_peminjaman_diinginkan;
    public $alasan_peminjaman;
    public $alamat_peminjam;

    protected $rules = [
        'tgl_peminjaman_diinginkan' => 'required|date|after:today',
        'tglKembaliRencana' => 'required|date|after:tgl_peminjaman_diinginkan|before_or_equal:tgl_peminjaman_max',
        'alasan_peminjaman' => 'required|min:10',
        'alamat_peminjam' => 'required|min:10',
    ];

    protected $messages = [
        'tgl_peminjaman_diinginkan.required' => 'Tanggal peminjaman harus diisi',
        'tgl_peminjaman_diinginkan.date' => 'Format tanggal tidak valid',
        'tgl_peminjaman_diinginkan.after' => 'Tanggal peminjaman harus setelah hari ini',
        'tglKembaliRencana.required' => 'Tanggal pengembalian harus diisi',
        'tglKembaliRencana.date' => 'Format tanggal tidak valid',
        'tglKembaliRencana.after' => 'Tanggal pengembalian harus setelah tanggal peminjaman',
        'tglKembaliRencana.before_or_equal' => 'Maksimal peminjaman adalah 14 hari',
        'alasan_peminjaman.required' => 'Alasan peminjaman harus diisi',
        'alasan_peminjaman.min' => 'Alasan peminjaman minimal 10 karakter',
        'alamat_peminjam.required' => 'Alamat peminjam harus diisi',
        'alamat_peminjam.min' => 'Alamat peminjam minimal 10 karakter',
    ];

    protected $listeners = [
        'closeDetailModal' => 'closeModal',
        'toggle-suka' => 'toggleSuka',
        'showDetailModal' => 'showModal'
    ];

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
        $this->validate();

        // Cek stok sekali lagi
        if ($this->book->stok < 1) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Maaf, stok buku sudah tidak tersedia.',
                'icon' => 'error'
            ]);
            return redirect()->route('books.index');
        }

        // Buat peminjaman dengan status pending (tidak mengurangi stok)
        Peminjaman::create([
            'id_user' => auth()->id(),
            'id_buku' => $this->book->id,
            'tgl_peminjaman' => $this->tgl_peminjaman_diinginkan,
            'tgl_kembali_rencana' => $this->tglKembaliRencana,
            'status' => 'pending',
            'total_denda' => 0,
            'alasan_peminjaman' => $this->alasan_peminjaman,
            'alamat_peminjam' => $this->alamat_peminjam
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

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedBook = null;
    }

    public function showModal($bookId)
    {
        $this->selectedBook = Buku::with(['ratings', 'suka'])->find($bookId);
        $this->isSukaByUser = auth()->check() ? auth()->user()->hasSukaBook($bookId) : false;
        $this->showDetailModal = true;
    }

    public function getTglPeminjamanMaxProperty()
    {
        return now()->addDays(14)->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.user.checkout');
    }
} 