<?php

namespace App\Observers;

use App\Models\Peminjaman;
use Carbon\Carbon;

class PeminjamanObserver
{
    public function retrieved(Peminjaman $peminjaman)
    {
        // Hanya cek untuk peminjaman yang sedang dipinjam atau terlambat
        if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'])) {
            return;
        }

        $today = Carbon::now()->startOfDay();
        $dueDate = Carbon::parse($peminjaman->tgl_kembali_rencana)->endOfDay();

        // Hanya hitung denda jika sudah masuk hari berikutnya
        if ($today->greaterThan($dueDate)) {
            // Update status menjadi terlambat jika belum
            if ($peminjaman->status !== 'terlambat') {
                $peminjaman->status = 'terlambat';
            }

            // Hitung jumlah hari terlambat
            $daysLate = $today->diffInDays($dueDate->startOfDay());
            
            // Hitung total denda
            $totalDenda = $daysLate * $peminjaman->buku->denda_harian;
            
            // Update total denda
            if ($peminjaman->total_denda !== $totalDenda) {
                $peminjaman->total_denda = $totalDenda;
                $peminjaman->save();
            }
        }
    }
} 