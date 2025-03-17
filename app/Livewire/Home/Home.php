<?php

namespace App\Livewire\Home;

use App\Models\Buku;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Home extends Component
{
    protected $listeners = ['refresh-books' => '$refresh'];

    public function render()
    {
        // Fetch popular books (most likes)
        $favoriteBooks = Buku::select(['id', 'judul', 'penulis', 'cover_img', 'deskripsi', 'stok'])
                             ->withCount('suka')
                             ->orderByDesc('suka_count')
                             ->take(5)
                             ->get();
        
        // Fetch highest rated books using simplified Wilson Score for MariaDB
        $topRatedBooks = Buku::select([
            'bukus.id',
            'bukus.judul',
            'bukus.penulis',
            'bukus.cover_img',
            'bukus.deskripsi',
            'bukus.stok',
            'bukus.kategori',
            DB::raw('COUNT(ratings.id) as total_ratings'),
            DB::raw('AVG(ratings.rating) as avg_rating'),
            DB::raw('(AVG(ratings.rating) * COUNT(ratings.rating) / (COUNT(ratings.rating) + 500)) as adjusted_score')
        ])
        ->leftJoin('ratings', 'bukus.id', '=', 'ratings.id_buku')
        ->groupBy([
            'bukus.id',
            'bukus.judul',
            'bukus.penulis',
            'bukus.cover_img',
            'bukus.deskripsi',
            'bukus.stok',
            'bukus.kategori'
        ])
        ->having('total_ratings', '>', 0)
        ->orderByDesc('adjusted_score')
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