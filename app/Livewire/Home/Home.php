<?php

namespace App\Livewire\Home;

use App\Models\Buku;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        // Fetch popular books (most likes)
        $favoriteBooks = Buku::select(['id', 'judul', 'penulis', 'cover_img', 'deskripsi', 'stok'])
                             ->withCount('suka')
                             ->orderByDesc('suka_count')
                             ->take(5)
                             ->get();
        
        // Fetch highest rated books
        $topRatedBooks = Buku::select(['id', 'judul', 'penulis', 'cover_img', 'deskripsi', 'stok'])
                             ->withAvg('ratings', 'rating')
                             ->having('ratings_avg_rating', '>', 0)
                             ->orderByDesc('ratings_avg_rating')
                             ->take(5)
                             ->get();
        
        // Get all available categories
        $categories = Buku::distinct('kategori')->pluck('kategori');

        return view('livewire.home.home', [
            'favoriteBooks' => $favoriteBooks,
            'topRatedBooks' => $topRatedBooks,
            'categories' => $categories
        ])->layout('layouts.user', [
            'title' => 'Home - PondokBaca'
        ]);
    }
}