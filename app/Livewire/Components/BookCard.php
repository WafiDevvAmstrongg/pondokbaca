<?php

namespace App\Livewire\Components;

use App\Models\Buku;
use App\Models\Suka;
use App\Models\Rating;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class BookCard extends Component
{
    public $showDetailModal = false;
    public $selectedBook = null;
    public $checkoutToken = null;
    public $isSukaByUser = false;
    public $books = null;
    public $ratings = [];
    public $showAllRatings = false;
    public $limitRatings = 3; // Default limit to show initially

    protected $listeners = [
        'closeDetailModal' => 'closeModal',
        'toggle-suka' => 'toggleSuka',
        'showDetailModal' => 'showDetail',
        'refresh-books' => '$refresh'
    ];

    public function mount($books = null)
    {
        if (is_object($books) && method_exists($books, 'items')) {
            $this->books = $books->items();
        } else {
            $this->books = $books;
        }
    }

    public function showDetail($bookId)
    {
        try {
            $this->selectedBook = Buku::with(['ratings', 'suka'])->find($bookId);
            $this->isSukaByUser = auth()->check() ? auth()->user()->hasSukaBook($bookId) : false;
            $this->loadRatings($bookId);
            $this->showAllRatings = false;
            $this->showDetailModal = true;
            $this->dispatch('modal-opened');
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'title' => 'Error!',
                'text' => 'Terjadi kesalahan saat memuat detail buku.',
                'icon' => 'error'
            ]);
        }
    }

    public function loadRatings($bookId)
    {
        try {
            // Load all ratings with user relationship and make sure it's a collection
            $this->ratings = Rating::with('user')
                              ->where('id_buku', $bookId)
                              ->orderBy('created_at', 'desc')
                              ->get()
                              ->toArray();
        } catch (\Exception $e) {
            $this->ratings = [];
        }
    }

    public function showAllRatings()
    {
        $this->showAllRatings = true;
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedBook = null;
        $this->ratings = [];
        $this->showAllRatings = false;
    }

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
            $this->isSukaByUser = false;
            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Buku telah dihapus dari daftar suka.',
                'icon' => 'success'
            ]);
        } else {
            Suka::create([
                'id_user' => $user->id,
                'id_buku' => $bookId
            ]);
            $this->isSukaByUser = true;
            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Buku telah ditambahkan ke daftar suka.',
                'icon' => 'success'
            ]);
        }

        // Refresh book data
        if ($this->selectedBook && $this->selectedBook->id === $bookId) {
            $this->selectedBook = Buku::with(['ratings', 'suka'])->find($bookId);
        }

        // Emit event untuk update tampilan di semua komponen
        $this->dispatch('refresh-books');
    }

    public function initiateCheckout()
    {
        if (!auth()->check()) {
            $this->dispatch('swal', [
                'title' => 'Perhatian!',
                'text' => 'Silakan login terlebih dahulu untuk meminjam buku.',
                'icon' => 'info'
            ]);
            $this->dispatch('open-login-modal');
            $this->closeModal();
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

        $token = Str::random(64);
        
        session([
            'checkout_token' => $token,
            'checkout_book_id' => $this->selectedBook->id,
            'checkout_expires_at' => now()->addHour()
        ]);

        $this->closeModal();
        
        $this->dispatch('redirect-to', [
            'url' => route('user.checkout', ['token' => $token])
        ]);
    }

    public function render()
    {
        if ($this->books === null) {
            $this->books = collect();
        }
        
        $displayRatings = $this->showAllRatings 
            ? $this->ratings 
            : array_slice($this->ratings, 0, $this->limitRatings);
    
        return view('livewire.components.book-card', [
            'books' => $this->books,
            'ratings' => $displayRatings
        ]);
    }
}