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
    
    protected $listeners = ['search-updated' => 'updateSearch'];
    protected $queryString = ['selectedCategory'];

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function updateSearch($search)
    {
        $this->search = $search;
        $this->resetPage();
    }
    
    public function selectCategory($category)
    {
        $this->selectedCategory = $this->selectedCategory === $category ? '' : $category;
        $this->resetPage();
    }

    public function render()
    {
        $query = Buku::query()
            ->select(['id', 'judul', 'penulis', 'cover_img', 'deskripsi', 'stok', 'kategori'])
            ->withAvg('ratings', 'rating')
            ->withCount('suka');

        if ($this->selectedCategory) {
            $query->where('kategori', $this->selectedCategory);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                  ->orWhere('penulis', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.home.books.index', [
            'books' => $query->paginate(15),
            'categories' => Buku::distinct('kategori')->pluck('kategori')
        ])->layout('layouts.user', [
            'title' => 'Daftar Buku - PondokBaca'
        ]);
    }
}