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
            ->with('buku')
            ->get();

        foreach ($activeLoans as $loan) {
            $today = Carbon::now();
            $dueDate = Carbon::parse($loan->tgl_kembali_rencana);

            // Jika sudah lewat tanggal kembali
            if ($today->greaterThan($dueDate)) {
                // Update status menjadi terlambat jika belum
                if ($loan->status !== 'terlambat') {
                    $loan->update(['status' => 'terlambat']);
                }

                // Hitung jumlah hari terlambat
                $daysLate = $today->diffInDays($dueDate);
                
                // Hitung total denda
                $totalDenda = $daysLate * $loan->buku->denda_harian;
                
                // Update total denda
                $loan->update(['total_denda' => $totalDenda]);
                
                $this->info("Loan ID {$loan->id} is late by {$daysLate} days. Late fee: {$totalDenda}");
            }
        }

        $this->info('Loan status check completed');
    }
} 