<?php

namespace App\Livewire\Components;

use App\Models\Buku;
use App\Models\Suka;
use Livewire\Component;
use Illuminate\Support\Str;

class BookCard extends Component
{
    public $showDetailModal = false;
    public $selectedBook = null;
    public $checkoutToken = null;
    public $isSukaByUser = false;
    public $books = null;

    protected $listeners = [
        'closeDetailModal' => 'closeModal',
        'toggle-suka' => 'toggleSuka',
        'showDetailModal' => 'showModal'
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
        $this->selectedBook = Buku::with(['ratings', 'suka'])->find($bookId);
        $this->isSukaByUser = auth()->check() ? auth()->user()->hasSukaBook($bookId) : false;
        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedBook = null;
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
            $message = 'Buku telah dihapus dari daftar suka.';
        } else {
            Suka::create([
                'id_user' => $user->id,
                'id_buku' => $bookId
            ]);
            $message = 'Buku telah ditambahkan ke daftar suka.';
        }

        // Refresh data buku yang diupdate
        foreach ($this->books as $key => $book) {
            if ($book->id === $bookId) {
                // Update suka_count
                $this->books[$key] = Buku::withCount('suka')
                    ->withAvg('ratings', 'rating')
                    ->find($bookId);
                
                // Update isSukaBy method
                $this->books[$key]->isSukaBy = function($userId) use ($user) {
                    return $this->hasSukaBook($this->books[$key]->id);
                };
            }
        }

        // Update selectedBook jika sedang dibuka
        if ($this->selectedBook && $this->selectedBook->id === $bookId) {
            $this->selectedBook = Buku::with(['ratings', 'suka'])
                ->withCount('suka')
                ->find($bookId);
            $this->isSukaByUser = !$existingSuka;
        }

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => $message,
            'icon' => 'success'
        ]);
    }

    private function hasSukaBook($bookId)
    {
        if (!auth()->check()) return false;
        return Suka::where('id_user', auth()->id())
                   ->where('id_buku', $bookId)
                   ->exists();
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
    
        return view('livewire.components.book-card', [
            'books' => $this->books
        ]);
    }
}