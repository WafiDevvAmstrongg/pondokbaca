<?php

// 📌 Namespace untuk menentukan lokasi class ini dalam struktur Livewire Components
namespace App\Livewire\Components;

// 📌 Mengimpor model yang dibutuhkan untuk pengelolaan buku dan fitur "suka"
use App\Models\Buku;
use App\Models\Suka;

// 📌 Mengimpor Livewire Component untuk membuat komponen dinamis
use Livewire\Component;

// 📌 Mengimpor Str untuk manipulasi string (digunakan untuk token checkout)
use Illuminate\Support\Str;

class BookCard extends Component
{
    // 📌 Variabel untuk mengontrol tampilan modal dan data yang ditampilkan
    public $showDetailModal = false; // Status modal detail buku
    public $selectedBook = null; // Buku yang sedang ditampilkan di modal detail
    public $checkoutToken = null; // Token unik untuk proses checkout
    public $isSukaByUser = false; // Status apakah buku disukai oleh pengguna
    public $books = null; // Koleksi buku yang akan ditampilkan di card
    public $showLikes = true; // Status untuk menampilkan jumlah like
    public $showRating = true; // Status untuk menampilkan rating buku

    // 📌 Event listeners untuk menangani interaksi Livewire
    protected $listeners = [
        'closeDetailModal' => 'closeModal',
        'toggle-suka' => 'toggleSuka',
        'showDetailModal' => 'showModal'
    ];

    /**
     * 📌 FUNGSI MOUNT
     * Digunakan saat komponen pertama kali diinisialisasi.
     * Memastikan `$books` adalah koleksi Eloquent dan menetapkan properti lainnya.
     */
    public function mount($books = null, $showLikes = true, $showRating = true)
    {
        if (is_array($books)) {
            $this->books = collect($books);
        } else {
            $this->books = $books;
        }
        $this->showLikes = $showLikes;
        $this->showRating = $showRating;
    }

    /**
     * 📌 FUNGSI MENAMPILKAN DETAIL BUKU
     * Menampilkan modal detail buku dengan informasi lengkap seperti rating & suka.
     */
    public function showDetail($bookId)
    {
        $this->selectedBook = Buku::with([
                'ratings.user',
                'suka.user'
            ])
            ->withCount('suka')
            ->withAvg('ratings', 'rating')
            ->find($bookId);

        // Refresh daftar buku agar data selalu konsisten
        if ($this->books) {
            $this->books = Buku::whereIn('id', $this->books->pluck('id'))
                ->with(['ratings.user', 'suka.user'])
                ->withCount('suka')
                ->withAvg('ratings', 'rating')
                ->get();
        }

        $this->showDetailModal = true;
    }

    /**
     * 📌 FUNGSI MENUTUP MODAL
     * Menutup modal detail buku dan memperbarui daftar buku agar tetap terkini.
     */
    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedBook = null;

        if ($this->books) {
            $this->books = Buku::whereIn('id', $this->books->pluck('id'))
                ->with(['ratings.user', 'suka.user'])
                ->withCount('suka')
                ->withAvg('ratings', 'rating')
                ->get();
        }
    }

    /**
     * 📌 FUNGSI TOGGLE "SUKA"
     * Menambahkan atau menghapus buku dari daftar suka pengguna.
     */
    public function toggleSuka($bookId)
    {
        if (!auth()->check()) {
            $this->dispatch('swal', [
                'title' => 'Perhatian!',
                'text' => 'Silakan login terlebih dahulu untuk menyukai buku.',
                'icon' => 'info'
            ]);
            $this->dispatch('open-login-modal');
            return;
        }

        $user = auth()->user();
        $existingSuka = Suka::where('id_user', $user->id)
                            ->where('id_buku', $bookId)
                            ->first();

        if ($existingSuka) {
            $existingSuka->delete();
            $message = 'Buku telah dihapus dari daftar suka.';
        } else {
            Suka::create([
                'id_user' => $user->id,
                'id_buku' => $bookId
            ]);
            $message = 'Buku telah ditambahkan ke daftar suka.';
        }

        // Refresh data buku agar jumlah "suka" diperbarui
        if ($this->books) {
            $this->books = Buku::whereIn('id', $this->books->pluck('id'))
                ->with(['ratings.user', 'suka.user'])
                ->withCount('suka')
                ->withAvg('ratings', 'rating')
                ->get();
        }

        // Jika buku yang disukai sedang dibuka dalam modal, perbarui datanya
        if ($this->selectedBook && $this->selectedBook->id === $bookId) {
            $this->selectedBook = Buku::with(['ratings.user', 'suka.user'])
                ->withCount('suka')
                ->withAvg('ratings', 'rating')
                ->find($bookId);
        }

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => $message,
            'icon' => 'success'
        ]);
    }

    /**
     * 📌 FUNGSI CEK BUKU DISUKAI OLEH PENGGUNA
     * Mengecek apakah pengguna sudah menyukai buku tersebut.
     */
    private function hasSukaBook($bookId)
    {
        if (!auth()->check()) return false;
        return Suka::where('id_user', auth()->id())
                   ->where('id_buku', $bookId)
                   ->exists();
    }

    /**
     * 📌 FUNGSI MEMULAI CHECKOUT
     * Jika pengguna belum login, akan diminta login terlebih dahulu.
     * Jika stok buku tersedia, akan diarahkan ke halaman checkout.
     */
    public function initiateCheckout()
    {
        if (!auth()->check()) {
            $this->closeModal();
            $this->dispatch('swal', [
                'title' => 'Perhatian!',
                'text' => 'Silakan login terlebih dahulu untuk meminjam buku.',
                'icon' => 'info'
            ]);
            $this->dispatch('open-login-modal');
            return;
        }

        if ($this->selectedBook->stok < 1) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Maaf, stok buku sedang tidak tersedia.',
                'icon' => 'error'
            ]);
            return;
        }

        // 📌 Membuat token unik untuk checkout
        $token = Str::random(64);

        // 📌 Menyimpan token dan data checkout ke session
        session([
            'checkout_token' => $token,
            'checkout_book_id' => $this->selectedBook->id,
            'checkout_expires_at' => now()->addHour()
        ]);

        return redirect()->route('user.checkout', ['token' => $token]);
    }

    /**
     * 📌 MENAMPILKAN VIEW BOOK CARD
     * Jika `$books` belum tersedia, buat koleksi kosong agar tidak error.
     */
    public function render()
    {
        if ($this->books === null) {
            $this->books = collect();
        }

        return view('livewire.components.book-card', [
            'books' => $this->books
        ]);
    }
}
