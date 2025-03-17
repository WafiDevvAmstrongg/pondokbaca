<?php

namespace App\Livewire\User\Peminjamans;

use App\Models\Peminjaman;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $status = '';
    public $search = '';
    public $selectedLoan = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function showProof($loanId)
    {
        $this->selectedLoan = Peminjaman::find($loanId);
    }

    public function closeModal()
    {
        $this->selectedLoan = null;
    }

    public function render()
    {
        $loans = Peminjaman::with(['buku'])
            ->where('id_user', auth()->id())
            ->when($this->search, function($query) {
                $query->whereHas('buku', function($q) {
                    $q->where('judul', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->status, function($query) {
                $query->where('status', $this->status);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.user.peminjamans.index', [
            'loans' => $loans
        ])->layout('layouts.user');
    }
}