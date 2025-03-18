<?php

namespace App\Livewire\User\Favorit;

use Livewire\Component;
use App\Models\Suka;
use App\Models\Buku;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;
    
    protected $listeners = [
        'favoriteToggled' => '$refresh'
    ];
    
    public function render()
    {
        // Get the book IDs from user's favorites
        $favoriteBookIds = Suka::where('id_user', Auth::id())
            ->pluck('id_buku');
            
        // Fetch the books with all necessary relations
        $favoriteBooks = Buku::whereIn('id', $favoriteBookIds)
            ->with([
                'ratings.user',
                'suka.user'
            ])
            ->withCount('suka')
            ->withAvg('ratings', 'rating')
            ->latest()
            ->paginate(12);
            
        return view('livewire.user.favorit.index', [
            'favoriteBooks' => $favoriteBooks
        ])->layout('layouts.user');
    }
}