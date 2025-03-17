<?php

namespace App\Livewire\User\Peminjamans;

use App\Models\Peminjaman;
use App\Models\Rating;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    public $status = '';
    public $search = '';
    public $selectedLoan = null;
    public $showingProof = false;
    public $showingRatingForm = false;
    public $showingConfirmation = false;
    public $showingSuccess = false;
    public $successMessage = '';
    
    // Rating form fields
    public $rating = 0;
    public $komentar = '';
    public $fotoReview = null;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'komentar' => 'required|string|min:5',
        'fotoReview' => 'nullable|image|max:2048',
    ];

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
        $this->showingProof = true;
    }

    public function returnBook($loanId)
    {
        $this->selectedLoan = Peminjaman::find($loanId);
        $this->showingConfirmation = true;
    }

    public function confirmReturn()
    {
        if ($this->selectedLoan && $this->selectedLoan->status === 'dipinjam') {
            $this->selectedLoan->status = 'dikembalikan';
            $this->selectedLoan->tgl_kembali_aktual = now();
            $this->selectedLoan->save();
            
            $this->showingConfirmation = false;
            $this->successMessage = 'Buku berhasil dikembalikan! Terima kasih telah menggunakan layanan perpustakaan kami.';
            $this->showingSuccess = true;
            
            // Refresh data tanpa reload penuh
            $this->dispatch('refreshData');
        }
    }
    
    // Tambahkan method ini
    public function refreshData()
    {
        // Kosongkan render cache
        $this->render();
    }

    public function showRatingForm($loanId)
    {
        $this->selectedLoan = Peminjaman::find($loanId);
        
        // Check if already rated
        $existingRating = Rating::where('id_user', auth()->id())
            ->where('id_buku', $this->selectedLoan->id_buku)
            ->first();
            
        if (!$existingRating) {
            $this->reset(['rating', 'komentar', 'fotoReview']);
            $this->showingRatingForm = true;
        } else {
            $this->successMessage = 'Anda sudah memberikan rating untuk buku ini sebelumnya.';
            $this->showingSuccess = true;
        }
    }

    public function setRating($value)
    {
        $this->rating = $value;
    }

    public function submitRating()
    {
        $this->validate();
        
        if (!$this->selectedLoan) {
            return;
        }
        
        $data = [
            'id_user' => auth()->id(),
            'id_buku' => $this->selectedLoan->id_buku,
            'rating' => $this->rating,
            'komentar' => $this->komentar,
        ];
        
        // Handle photo upload if provided
        if ($this->fotoReview) {
            $path = $this->fotoReview->store('rating-photos', 'public');
            $data['foto_review'] = $path;
        }
        
        // Create the rating
        Rating::create($data);
        
        // Update loan status to completed if necessary
        $this->selectedLoan->save();
        
        // Reset and show success message
        $this->showingRatingForm = false;
        $this->successMessage = 'Terima kasih atas feedback Anda!';
        $this->showingSuccess = true;
    }

    public function closeModal()
    {
        $this->selectedLoan = null;
        $this->showingProof = false;
        $this->showingRatingForm = false;
        $this->showingConfirmation = false;
        $this->showingSuccess = false;
        $this->reset(['rating', 'komentar', 'fotoReview']);
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
            
        // Add flag to check if book has been rated
        foreach ($loans as $loan) {
            $loan->hasRating = Rating::where('id_user', auth()->id())
                ->where('id_buku', $loan->id_buku)
                ->exists();
        }

        return view('livewire.user.peminjamans.index', [
            'loans' => $loans
        ])->layout('layouts.user');
    }
}