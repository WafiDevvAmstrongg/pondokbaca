<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Peminjaman;

class MyLoans extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    public function render()
    {
        $loans = Peminjaman::with('buku')
            ->where('id_user', auth()->id())
            ->when($this->status, function($query) {
                $query->where('status', $this->status);
            })
            ->when($this->search, function($query) {
                $query->whereHas('buku', function($q) {
                    $q->where('judul', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.user.my-loans', [
            'loans' => $loans
        ])->layout('components.layouts.root', [
            'title' => 'My Loans'
        ]);
    }
} 