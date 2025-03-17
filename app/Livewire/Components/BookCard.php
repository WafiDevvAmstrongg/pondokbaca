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
    public $books = [];
    public $isSukaByUser = false;

    public function mount($books = null)
    {
        $this->books = $books;
    }

    protected $listeners = ['closeDetailModal' => 'closeModal'];

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
        $this->checkoutToken = null;
    }

    public function toggleSuka($bookId)
    {
        if (!auth()->check()) {
            $this->dispatch('open-login-modal');
            return;
        }

        $user = auth()->user();
        $book = Buku::find($bookId);

        if (!$book) {
            return;
        }

        $existingSuka = Suka::where('id_user', $user->id)
                           ->where('id_buku', $bookId)
                           ->first();

        if ($existingSuka) {
            $existingSuka->delete();
        } else {
            Suka::create([
                'id_user' => $user->id,
                'id_buku' => $bookId
            ]);
        }

        // Refresh the books collection to update the suka count
        $this->dispatch('refresh-books')->to('home.books.index');
    }

    public function checkout()
    {
        if (!auth()->check()) {
            $this->dispatch('open-login-modal');
            return;
        }

        if (!$this->selectedBook->isAvailable()) {
            $this->dispatch('swal', [
                'title' => 'Maaf!',
                'text' => 'Buku ini sedang tidak tersedia.',
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
        return view('livewire.components.book-card');
    }
}