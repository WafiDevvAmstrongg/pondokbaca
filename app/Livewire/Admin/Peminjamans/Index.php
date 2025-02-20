<?php

namespace App\Livewire\Admin\Peminjamans;

use App\Models\Peminjaman;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $loans = Peminjaman::with(['user', 'buku'])
            ->when($this->search, function($query) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%'.$this->search.'%');
                })->orWhereHas('buku', function($q) {
                    $q->where('judul', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->status, function($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.loans', compact('loans'));
    }
} 