<?php

namespace App\Livewire;

use App\Models\Buku;
use Livewire\Component;

class RecommendedBooks extends Component
{
    public function render()
    {
        return view('livewire.recommended-books', [
            'books' => Buku::take(5)
                          ->get()
        ]);
    }
}
