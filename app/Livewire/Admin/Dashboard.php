<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Buku;
use App\Models\Peminjaman;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalUsers = User::count();
        $totalBooks = Buku::count();
        $totalLoans = Peminjaman::count();
        $activeLoans = Peminjaman::whereIn('status', ['diproses', 'dikirim', 'dipinjam'])->count();
        
        $recentLoans = Peminjaman::with(['user', 'buku'])
                                ->latest()
                                ->take(5)
                                ->get();
    
        // Optimasi query untuk $loans
        $loans = Peminjaman::with(['user', 'buku'])->latest()->take(10)->get(); // Ambil 10 data terbaru
    
        return view('livewire.admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalBooks' => $totalBooks,
            'totalLoans' => $totalLoans,
            'activeLoans' => $activeLoans,
            'recentLoans' => $recentLoans,
            'loans' => $loans // Kirim ke view
        ])->layout('layouts.admin', [
            'title' => 'Admin Dashboard'
        ]);
    }
    
} 