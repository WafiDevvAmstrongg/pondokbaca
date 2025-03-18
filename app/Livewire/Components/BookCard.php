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
        'showDetailModal' => 'showModal'
    ];

    public function mount($books = null)
    {
        if (is_object($books) && method_exists($books, 'items')) {
            $this->books = collect($books->items())->map(function ($book) {
                return (object) $book;
            })->toArray();
        } else if (is_array($books)) {
            $this->books = collect($books)->map(function ($book) {
                return (object) $book;
            })->toArray();
        } else {
            $this->books = $books;
        }
    }

    public function showDetail($bookId)
    {
        $this->selectedBook = Buku::with(['ratings', 'suka'])->find($bookId);
        $this->isSukaByUser = auth()->check() ? $this->selectedBook->isSukaBy(auth()->id()) : false;
        
        // Load ratings with user relationship
        $this->loadRatings($bookId);
        
        $this->showAllRatings = false;
        $this->showDetailModal = true;
    }

    public function loadRatings($bookId)
    {
        // Load all ratings with user relationship and make sure it's a collection
        $this->ratings = Rating::with('user')
                          ->where('id_buku', $bookId)
                          ->orderBy('created_at', 'desc')
                          ->get()
                          ->toArray(); // Convert to array to ensure proper serialization with Livewire
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
        } else {
            Suka::create([
                'id_user' => $user->id,
                'id_buku' => $bookId
            ]);
            $this->isSukaByUser = true;
        }

        // Refresh book data dengan eager loading yang lengkap
        $updatedBook = Buku::withCount('suka')
            ->withAvg('ratings', 'rating')
            ->with(['suka', 'ratings'])
            ->find($bookId);

        // Update selected book jika sedang ditampilkan di modal
        if ($this->selectedBook && $this->selectedBook->id === $bookId) {
            $this->selectedBook = $updatedBook;
        }

        // Update books collection dengan cara yang lebih aman
        if ($this->books) {
            $this->books = collect($this->books)->map(function($book) use ($updatedBook) {
                if ((is_object($book) ? $book->id : $book['id']) === $updatedBook->id) {
                    // Convert to array and ensure all properties are accessible
                    return (object) array_merge((array) $book, [
                        'id' => $updatedBook->id,
                        'suka_count' => $updatedBook->suka_count,
                        'ratings_avg_rating' => $updatedBook->ratings_avg_rating,
                        'isSukaByUser' => auth()->check() ? $updatedBook->isSukaBy(auth()->id()) : false
                    ]);
                }
                return (object) $book;
            })->toArray();
        }

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => $existingSuka ? 'Buku telah dihapus dari daftar suka.' : 'Buku telah ditambahkan ke daftar suka.',
            'icon' => 'success'
        ]);

        // Dispatch event untuk refresh komponen lain
        $this->dispatch('refresh-books');
    }

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

        // Generate token unik untuk checkout
        $token = Str::random(64);
        
        // Simpan token dan data checkout ke session
        session([
            'checkout_token' => $token,
            'checkout_book_id' => $this->selectedBook->id,
            'checkout_expires_at' => now()->addHour()
        ]);

        return redirect()->route('user.checkout', ['token' => $token]);
    }

    public function render()
    {
        // Only fetch books if none were passed
        if ($this->books === null) {
            // This instance is only for showing the modal
            $this->books = collect();
        }
        
        // Handle ratings pagination manually since we have an array
        $displayRatings = $this->showAllRatings 
            ? $this->ratings 
            : array_slice($this->ratings, 0, $this->limitRatings);
    
        return view('livewire.components.book-card', [
            'books' => $this->books,
            'ratings' => $displayRatings
        ]);
    }
}