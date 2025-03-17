<?php

namespace App\Livewire\User\Pembayaran;

use App\Models\Peminjaman;
use Livewire\Component;
use Carbon\Carbon;

class Index extends Component
{
    public $showPaymentModal = false;
    public $selectedLoan = null;
    public $paymentMethod = '';
    public $processingPayment = false;

    public function mount()
    {
        // Reset any previous payment sessions
        session()->forget('payment_success');
    }

    public function initiatePayment($loanId)
    {
        $this->selectedLoan = Peminjaman::findOrFail($loanId);
        $this->showPaymentModal = true;
    }

    public function processPayment()
    {
        $this->processingPayment = true;

        // Simulate payment processing
        sleep(2);

        // Update loan status
        $this->selectedLoan->update([
            'status' => 'dikembalikan',
            'total_denda' => 0,
            'tgl_kembali_aktual' => now()
        ]);

        session()->flash('message', 'Pembayaran berhasil! Buku telah dikembalikan.');
        
        $this->showPaymentModal = false;
        $this->processingPayment = false;
        $this->dispatch('payment-success');
    }

    public function render()
    {
        $loans = Peminjaman::where('id_user', auth()->id())
            ->where(function($query) {
                $query->where('status', 'terlambat')
                    ->orWhere('total_denda', '>', 0);
            })
            ->with('buku')
            ->latest()
            ->get();

        $totalDenda = $loans->sum('total_denda');

        return view('livewire.user.pembayaran.index', [
            'loans' => $loans,
            'totalDenda' => $totalDenda
        ])->layout('layouts.app');
    }
} 