<?php

namespace App\Observers;

use App\Models\Peminjaman;
use App\Models\Buku;
use Carbon\Carbon;
use App\Events\LoanFineUpdated;

class PeminjamanObserver
{
    public function updated(Peminjaman $peminjaman)
    {
        // Jika status berubah menjadi 'diproses', kurangi stok 1
        if ($peminjaman->status === 'diproses' && $peminjaman->getOriginal('status') === 'pending') {
            $buku = Buku::find($peminjaman->id_buku);
            $buku->update([
                'stok' => $buku->stok - 1
            ]);
        }
        
        // Jika status berubah menjadi 'dikembalikan', tambah stok 1
        if ($peminjaman->status === 'dikembalikan' && 
            in_array($peminjaman->getOriginal('status'), ['dipinjam', 'terlambat'])) {
            $buku = Buku::find($peminjaman->id_buku);
            $buku->update([
                'stok' => $buku->stok + 1
            ]);
            
            // Set tanggal kembali aktual
            $peminjaman->update([
                'tgl_kembali_aktual' => now(),
                'total_denda' => 0 // Reset denda jika ada
            ]);
        }
    }

    public function retrieved(Peminjaman $peminjaman)
    {
        if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'])) {
            return;
        }

        $today = Carbon::now()->startOfDay();
        $dueDate = Carbon::parse($peminjaman->tgl_kembali_rencana)->endOfDay();

        if ($today->greaterThan($dueDate)) {
            $oldFine = $peminjaman->total_denda;
            
            $daysLate = $today->diffInDays($dueDate->startOfDay());
            $totalDenda = $daysLate * $peminjaman->buku->denda_harian;
            
            if ($peminjaman->total_denda !== $totalDenda) {
                $peminjaman->status = 'terlambat';
                $peminjaman->total_denda = $totalDenda;
                $peminjaman->save();

                event(new LoanFineUpdated($peminjaman, $oldFine, $totalDenda));
            }
        }
    }
} 