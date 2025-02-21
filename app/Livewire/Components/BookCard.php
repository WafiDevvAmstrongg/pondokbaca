<?php

namespace App\Livewire\Components;

use App\Models\Buku;
use Livewire\Component;
use Illuminate\Support\Str;

class BookCard extends Component
{
    public $showDetailModal = false;
    public $selectedBook = null;
    public $checkoutToken = null;

    protected $listeners = ['closeDetailModal' => 'closeModal'];

    public function showDetail($bookId)
    {
        $this->selectedBook = Buku::with(['ratings', 'suka'])->find($bookId);
        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedBook = null;
    }

    public function initiateCheckout()
    {
        if (!auth()->check()) {
            $this->closeModal();
            $this->dispatch('showAlert', [
                'type' => 'info',
                'message' => 'Anda harus login terlebih dahulu untuk meminjam buku.'
            ]);
            $this->dispatch('open-login-modal');
            return;
        }

        if ($this->selectedBook->stok < 1) {
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Maaf, stok buku ini sedang tidak tersedia.'
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
        $books = Buku::select(['id', 'judul', 'penulis', 'cover_img', 'deskripsi', 'stok'])
                     ->withAvg('ratings', 'rating')
                     ->withCount('suka')
                     ->take(5)
                     ->get();

        return view('livewire.components.book-card', compact('books'));
    }
}