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
    public $limitRatings = 3;

    protected $listeners = [
        'closeDetailModal' => 'closeModal',
        'toggle-suka' => 'toggleSuka',
        'showDetailModal' => 'showModal'
    ];

    public function mount($books = null)
    {
        if (is_object($books) && method_exists($books, 'items')) {
            $this->books = collect($books->items())->map(function ($book) {
                return $this->formatBookData($book);
            })->toArray();
        } else if (is_array($books)) {
            $this->books = collect($books)->map(function ($book) {
                return $this->formatBookData($book);
            })->toArray();
        } else {
            $this->books = [];
        }
    }

    protected function formatBookData($book)
    {
        $data = [
            'id' => is_array($book) ? ($book['id'] ?? null) : ($book->id ?? null),
            'judul' => is_array($book) ? ($book['judul'] ?? '') : ($book->judul ?? ''),
            'penulis' => is_array($book) ? ($book['penulis'] ?? '') : ($book->penulis ?? ''),
            'cover_img' => is_array($book) ? ($book['cover_img'] ?? '') : ($book->cover_img ?? ''),
            'deskripsi' => is_array($book) ? ($book['deskripsi'] ?? '') : ($book->deskripsi ?? ''),
            'stok' => is_array($book) ? ($book['stok'] ?? 0) : ($book->stok ?? 0),
            'suka_count' => is_array($book) ? ($book['suka_count'] ?? 0) : ($book->suka_count ?? 0),
            'ratings_avg_rating' => is_array($book) ? ($book['ratings_avg_rating'] ?? 0) : ($book->ratings_avg_rating ?? 0),
        ];

        if (auth()->check()) {
            $data['isSukaByUser'] = is_array($book) 
                ? ($book['isSukaByUser'] ?? false) 
                : (method_exists($book, 'isSukaBy') ? $book->isSukaBy(auth()->id()) : false);
        } else {
            $data['isSukaByUser'] = false;
        }

        return (object) $data;
    }

    public function showDetail($bookId)
    {
        $this->selectedBook = Buku::with(['ratings', 'suka'])->find($bookId);
        $this->isSukaByUser = auth()->check() ? $this->selectedBook->isSukaBy(auth()->id()) : false;
        $this->loadRatings($bookId);
        $this->showAllRatings = false;
        $this->showDetailModal = true;
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
                    return (object) [
                        'id' => $updatedBook->id,
                        'judul' => $updatedBook->judul,
                        'penulis' => $updatedBook->penulis,
                        'cover_img' => $updatedBook->cover_img,
                        'deskripsi' => $updatedBook->deskripsi,
                        'stok' => $updatedBook->stok,
                        'suka_count' => $updatedBook->suka_count,
                        'ratings_avg_rating' => $updatedBook->ratings_avg_rating,
                        'isSukaByUser' => auth()->check() ? $updatedBook->isSukaBy(auth()->id()) : false
                    ];
                }
                return $book;
            })->toArray();
        }

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => $existingSuka ? 'Buku telah dihapus dari daftar suka.' : 'Buku telah ditambahkan ke daftar suka.',
            'icon' => 'success'
        ]);

        $this->dispatch('refresh-books');
    }

    public function loadRatings($bookId)
    {
        $this->ratings = Rating::with('user')
                          ->where('id_buku', $bookId)
                          ->orderBy('created_at', 'desc')
                          ->get()
                          ->toArray();
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