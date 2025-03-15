<?php

namespace App\Livewire\Home\Books;

use App\Models\Buku;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $selectedCategory = '';
    public $search = '';
    
    // Reset pagination ketika filter berubah
    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Buku::query()
            ->select(['id', 'judul', 'penulis', 'cover_img', 'deskripsi', 'stok', 'kategori'])
            ->withAvg('ratings', 'rating')
            ->withCount('suka');

        // Filter berdasarkan kategori
        if ($this->selectedCategory) {
            $query->where('kategori', $this->selectedCategory);
        }

        // Filter berdasarkan pencarian
        if ($this->search) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('penulis', 'like', '%' . $this->search . '%');
            });
        }

        $books = $query->paginate(15);
        $categories = Buku::distinct('kategori')->pluck('kategori');

        return view('livewire.home.books.index', [
            'books' => $books,
            'categories' => $categories
        ])->layout('layouts.user', [
            'title' => 'Daftar Buku - PondokBaca'
        ]);
    }
}
