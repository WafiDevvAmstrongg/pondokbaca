<?php

namespace App\Livewire\Layouts;

use App\Models\Buku;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Navigation extends Component
{
    public $search = '';
    public $searchResults = [];
    public $showDropdown = false;
    public $isSearching = false;

    protected $listeners = ['closeDetailModal' => 'resetSearch'];

    public function updatedSearch()
    {
        if (strlen($this->search) < 2) {
            $this->searchResults = [];
            $this->showDropdown = false;
            return;
        }

        if (request()->routeIs('books')) {
            $this->dispatch('search-updated', search: $this->search);
        }

        $this->isSearching = true;
        $this->searchResults = Buku::where('judul', 'like', '%' . $this->search . '%')
            ->orWhere('penulis', 'like', '%' . $this->search . '%')
            ->take(5)
            ->get();
        $this->showDropdown = true;
        $this->isSearching = false;
    }

    public function showBookDetail($bookId)
    {
        $this->dispatch('showDetailModal', bookId: $bookId);
        $this->resetSearch();
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->searchResults = [];
        $this->showDropdown = false;
    }

    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.layouts.navigation');
    }
}