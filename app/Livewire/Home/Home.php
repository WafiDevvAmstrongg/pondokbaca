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
        $favoriteBooks = Buku::select([
                'bukus.id', 
                'bukus.judul', 
                'bukus.penulis', 
                'bukus.cover_img', 
                'bukus.deskripsi', 
                'bukus.stok'
            ])
            ->with('suka') // Eager load suka relation
            ->withCount('suka')
            ->withAvg('ratings', 'rating')
            ->orderByDesc('suka_count')
            ->take(5)
            ->get();
        
        // Fetch highest rated books using Wilson Score confidence formula
        // This balances average rating with number of ratings and borrowing frequency
        $topRatedBooks = Buku::select([
                'bukus.id',
                'bukus.judul',
                'bukus.penulis',
                'bukus.cover_img',
                'bukus.deskripsi',
                'bukus.stok'
            ])
            ->with('suka') // Eager load suka relation
            ->withCount('suka')
            ->withAvg('ratings', 'rating')
            ->leftJoin('ratings', 'bukus.id', '=', 'ratings.id_buku')
            ->leftJoin('peminjamans', 'bukus.id', '=', 'peminjamans.id_buku')
            ->select([
                'bukus.id',
                'bukus.judul',
                'bukus.penulis',
                'bukus.cover_img',
                'bukus.deskripsi',
                'bukus.stok',
                DB::raw('COUNT(DISTINCT ratings.id) as total_ratings'),
                DB::raw('AVG(ratings.rating) as ratings_avg_rating'),
                DB::raw('COUNT(DISTINCT peminjamans.id) as borrow_count'),
                // Formula that considers both ratings and borrowing frequency
                DB::raw('(AVG(ratings.rating) * COUNT(DISTINCT ratings.id) / (COUNT(DISTINCT ratings.id) + 10) + 
                         (COUNT(DISTINCT peminjamans.id) / 100)) as adjusted_score')
            ])
            ->groupBy([
                'bukus.id',
                'bukus.judul',
                'bukus.penulis',
                'bukus.cover_img',
                'bukus.deskripsi',
                'bukus.stok'
            ])
            ->having('total_ratings', '>', 0)
            ->orderByDesc('adjusted_score')
            ->take(5)
            ->get();
        
        // Add isSukaBy method to check if current user has liked each book
        if (auth()->check()) {
            $userId = auth()->id();
            $favoriteBooks->each(function($book) use ($userId) {
                $book->isSukaBy = function($id) use ($book, $userId) {
                    return $book->suka->contains('id_user', $userId);
                };
            });
            
            $topRatedBooks->each(function($book) use ($userId) {
                $book->isSukaBy = function($id) use ($book, $userId) {
                    return $book->suka->contains('id_user', $userId);
                };
            });
        }
        
        // Get all available categories
        $categories = [
            'al-quran', 'hadis', 'fikih', 'akidah', 'sirah', 
            'tafsir', 'tarbiyah', 'sejarah', 'buku-anak', 'novel'
        ];

        return view('livewire.home.home', [
            'favoriteBooks' => $favoriteBooks,
            'topRatedBooks' => $topRatedBooks,
            'categories' => $categories
        ])->layout('layouts.user', [
            'title' => 'Home - PondokBaca'
        ]);
    }
}