<?php

namespace App\Livewire\Home;

use App\Models\Buku;
use App\Models\Suka;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Home extends Component
{
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

        // Refresh the component to update the suka count
        $this->dispatch('$refresh');
    }

    public function render()
    {
        // Fetch popular books (most likes)
        $favoriteBooks = Buku::select(['id', 'judul', 'penulis', 'cover_img', 'deskripsi', 'stok'])
                             ->withCount('suka')
                             ->withAvg('ratings', 'rating')
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
            'bukus.stok'
        ])
        ->withCount('suka')
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