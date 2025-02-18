<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Bookmark;

class MyBooks extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $bookmarks = Bookmark::with('buku')
            ->where('id_user', auth()->id())
            ->whereHas('buku', function($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('penulis', 'like', '%' . $this->search . '%');
            })
            ->paginate(12);

        return view('livewire.user.my-books', [
            'bookmarks' => $bookmarks
        ])->layout('components.layouts.root', [
            'title' => 'My Books'
        ]);
    }
} 