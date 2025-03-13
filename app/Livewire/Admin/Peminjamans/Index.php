<?php

namespace App\Livewire\Admin\Peminjamans;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
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
        $totalUsers = User::count();
        $totalBooks = Buku::count();
        $totalLoans = Peminjaman::count();
        $activeLoans = Peminjaman::whereIn('status', ['diproses', 'dikirim', 'dipinjam'])->count();
        
        $loans = Peminjaman::with(['user', 'buku'])
                            ->when($this->search, function ($query) {
                                $query->whereHas('user', function ($q) {
                                    $q->where('name', 'like', '%' . $this->search . '%');
                                })->orWhereHas('buku', function ($q) {
                                    $q->where('judul', 'like', '%' . $this->search . '%');
                                });
                            })
                            ->when($this->status, function ($query) {
                                $query->where('status', $this->status);
                            })
                            ->latest()
                            ->paginate(10); // Pastikan paginate() digunakan
    
        return view('livewire.admin.peminjamans.index', [
            'totalUsers' => $totalUsers,
            'totalBooks' => $totalBooks,
            'totalLoans' => $totalLoans,
            'activeLoans' => $activeLoans,
            'loans' => $loans
        ])->layout('layouts.admin', [
            'title' => 'Admin Dashboard',
        ]);
    }
    
} 