<?php

namespace App\Livewire;

use App\Models\Buku;
use Livewire\Component;

class RecommendedBooks extends Component
{
    public function render()
    {
        $books = Buku::select(['id', 'judul', 'penulis', 'cover_img'])
                     ->take(5)
                     ->get();

        return view('livewire.recommended-books', compact('books'));
    }
}