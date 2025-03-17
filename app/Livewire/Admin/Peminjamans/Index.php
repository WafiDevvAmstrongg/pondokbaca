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
    public $showRejectModal = false;
    public $selectedLoanId = null;
    public $alasanPenolakan = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showRejectModal($loanId)
    {
        $this->selectedLoanId = $loanId;
        $this->showRejectModal = true;
        $this->alasanPenolakan = '';
    }

    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->selectedLoanId = null;
        $this->alasanPenolakan = '';
    }

    public function approve($loanId)
    {
        $loan = Peminjaman::findOrFail($loanId);
        
        if ($loan->status === 'pending') {
            $loan->update([
                'status' => 'diproses',
                'id_staff' => auth()->id()
            ]);

            session()->flash('message', 'Peminjaman berhasil disetujui.');
        }
    }

    public function reject()
    {
        $this->validate([
            'alasanPenolakan' => 'required|min:10'
        ], [
            'alasanPenolakan.required' => 'Alasan penolakan harus diisi.',
            'alasanPenolakan.min' => 'Alasan penolakan minimal 10 karakter.'
        ]);

        $loan = Peminjaman::findOrFail($this->selectedLoanId);
        
        if ($loan->status === 'pending') {
            $loan->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $this->alasanPenolakan,
                'id_staff' => auth()->id()
            ]);

            session()->flash('message', 'Peminjaman telah ditolak.');
        }

        $this->closeRejectModal();
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