<?php

namespace App\Livewire\Components;

use App\Models\Buku;
use Livewire\Component;

class BookCard extends Component
{
    public function render()
    {
        $books = Buku::select(['id', 'judul', 'penulis', 'cover_img'])
                     ->take(5)
                     ->get();

        return view('livewire.components.book-card', compact('books'));
    }
}