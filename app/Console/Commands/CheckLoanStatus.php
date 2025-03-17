<?php

namespace App\Console\Commands;

use App\Models\Peminjaman;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckLoanStatus extends Command
{
    protected $signature = 'loans:check-status';
    protected $description = 'Check loan status and calculate late fees';

    public function handle()
    {
        // Ambil semua peminjaman yang masih dipinjam atau terlambat
        $activeLoans = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])
            ->where('tgl_kembali_rencana', '<', now()->startOfDay()) // Pastikan sudah lewat hari
            ->with('buku')
            ->get();

        foreach ($activeLoans as $loan) {
            $today = Carbon::now()->startOfDay();
            $dueDate = Carbon::parse($loan->tgl_kembali_rencana)->endOfDay(); // Gunakan endOfDay
            
            // Hanya hitung denda jika sudah masuk hari berikutnya
            if ($today->greaterThan($dueDate)) {
                // Hitung jumlah hari terlambat
                $daysLate = $today->diffInDays($dueDate->startOfDay());
                
                // Hitung total denda
                $totalDenda = $daysLate * $loan->buku->denda_harian;
                
                // Update status dan denda
                $loan->update([
                    'status' => 'terlambat',
                    'total_denda' => $totalDenda
                ]);
                
                $this->info("Loan ID {$loan->id} is late by {$daysLate} days. Late fee: {$totalDenda}");
            }
        }

        $this->info('Loan status check completed');
    }
} 