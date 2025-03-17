<?php

namespace App\Livewire\Home;

use App\Models\Buku;
use Illuminate\Support\Facades\DB;
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
        
        // Fetch highest rated books using Wilson Score
        $confidence = 1.96; // 95% confidence interval
        $topRatedBooks = Buku::select([
            'bukus.*',
            DB::raw('COUNT(ratings.id) as total_ratings'),
            DB::raw('AVG(ratings.rating) as avg_rating'),
            DB::raw("(
                (AVG(ratings.rating) + $confidence * $confidence / (2 * COUNT(ratings.rating)) 
                - $confidence * SQRT((AVG(ratings.rating) * (1 - AVG(ratings.rating)) 
                + $confidence * $confidence / (4 * COUNT(ratings.rating))) / COUNT(ratings.rating))) 
                / (1 + $confidence * $confidence / COUNT(ratings.rating))
                AS wilson_score
            )")
        ])
        ->leftJoin('ratings', 'bukus.id', '=', 'ratings.id_buku')
        ->groupBy('bukus.id')
        ->having('total_ratings', '>', 0)
        ->orderByDesc('wilson_score')
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