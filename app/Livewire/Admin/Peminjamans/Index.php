<?php

namespace App\Livewire\Admin\Peminjamans;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $status = '';
    public $showRejectModal = false;
    public $showShipmentModal = false;
    public $selectedLoanId = null;
    public $alasanPenolakan = '';
    public $buktiPengiriman;
    public $nomorResi = '';
    public $catatanPengiriman = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openRejectModal($loanId)
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

    public function openShipmentModal($loanId)
    {
        $loan = Peminjaman::findOrFail($loanId);
        $this->selectedLoanId = $loanId;
        $this->nomorResi = $loan->nomor_resi;
        $this->showShipmentModal = true;
        $this->buktiPengiriman = null;
    }

    public function closeShipmentModal()
    {
        $this->showShipmentModal = false;
        $this->selectedLoanId = null;
        $this->buktiPengiriman = null;
        $this->nomorResi = '';
    }

    public function confirmShipment()
    {
        $this->validate([
            'buktiPengiriman' => 'required|image|max:2048', // max 2MB
        ], [
            'buktiPengiriman.required' => 'Bukti pengiriman harus diupload.',
            'buktiPengiriman.image' => 'File harus berupa gambar.',
            'buktiPengiriman.max' => 'Ukuran gambar maksimal 2MB.'
        ]);

        $loan = Peminjaman::findOrFail($this->selectedLoanId);
        
        if ($loan->status === 'diproses') {
            // Upload bukti pengiriman
            $path = $this->buktiPengiriman->store('bukti-pengiriman', 'public');

            $loan->update([
                'status' => 'dikirim',
                'bukti_pengiriman' => $path,
                'tgl_dikirim' => now()
            ]);

            session()->flash('message', 'Peminjaman berhasil dikonfirmasi pengirimannya.');
            
            // Tambahkan dispatch untuk memaksa re-render
            $this->dispatch('$refresh');
        }

        $this->closeShipmentModal();
    }

    public function approve($loanId)
    {
        $loan = Peminjaman::findOrFail($loanId);
        
        if ($loan->status === 'pending') {
            // Generate nomor resi saat peminjaman disetujui
            $nomorResi = 'PJM-' . strtoupper(Str::random(8)) . '-' . date('Ymd');
            
            $loan->update([
                'status' => 'diproses',
                'id_staff' => auth()->id(),
                'nomor_resi' => $nomorResi
            ]);

            session()->flash('message', 'Peminjaman berhasil disetujui.');
            
            // Tambahkan dispatch untuk memaksa re-render
            $this->dispatch('$refresh');
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
        $this->dispatch('$refresh');
    }

    public function markAsReceived($loanId)
    {
        $loan = Peminjaman::findOrFail($loanId);
        
        if ($loan->status === 'dikirim') {
            $loan->update([
                'status' => 'dipinjam',
                'tgl_peminjaman' => now() // Catat tanggal mulai peminjaman
            ]);

            session()->flash('message', 'Status peminjaman berhasil diupdate ke Dipinjam.');
            $this->dispatch('$refresh');
        }
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
    
        // Hitung denda untuk setiap peminjaman yang ditampilkan
        foreach ($loans as $loan) {
            if (in_array($loan->status, ['dipinjam', 'terlambat']) && $loan->tgl_kembali_rencana) {
                $today = Carbon::now();
                $dueDate = Carbon::parse($loan->tgl_kembali_rencana);
                
                if ($today->greaterThan($dueDate)) {
                    $daysLate = $today->diffInDays($dueDate);
                    $totalDenda = $daysLate * $loan->buku->denda_harian;
                    
                    // Update hanya jika total denda berubah
                    if ($loan->total_denda !== $totalDenda) {
                        $loan->update([
                            'status' => 'terlambat',
                            'total_denda' => $totalDenda
                        ]);
                    }
                }
            }
        }

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