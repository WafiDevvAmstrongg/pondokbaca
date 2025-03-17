<?php

namespace App\Observers;

use App\Models\Peminjaman;
use App\Models\Buku;
use Carbon\Carbon;

class PeminjamanObserver
{
    public function updated(Peminjaman $peminjaman)
    {
        // Jika status berubah menjadi 'diproses', kurangi stok
        if ($peminjaman->status === 'diproses' && $peminjaman->getOriginal('status') === 'pending') {
            $buku = Buku::find($peminjaman->id_buku);
            $buku->decrement('stok');
        }
        
        // Jika status berubah menjadi 'dikembalikan', tambah stok
        if ($peminjaman->status === 'dikembalikan' && 
            in_array($peminjaman->getOriginal('status'), ['dipinjam', 'terlambat'])) {
            // Get the book instance here before using it
            $buku = Buku::find($peminjaman->id_buku);
            $buku->increment('stok');
            
            // Set tanggal kembali aktual
            $peminjaman->update([
                'tgl_kembali_aktual' => now(),
                'total_denda' => 0 // Reset denda jika ada
            ]);
        }
    }

    public function retrieved(Peminjaman $peminjaman)
    {
        // Hanya cek keterlambatan untuk status dipinjam/terlambat
        if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'])) {
            return;
        }

        $today = Carbon::now()->startOfDay();
        $dueDate = Carbon::parse($peminjaman->tgl_kembali_rencana)->endOfDay();

        if ($today->greaterThan($dueDate)) {
            if ($peminjaman->status !== 'terlambat') {
                $peminjaman->status = 'terlambat';
            }

            $daysLate = $today->diffInDays($dueDate->startOfDay());
            $totalDenda = $daysLate * $peminjaman->buku->denda_harian;
            
            if ($peminjaman->total_denda !== $totalDenda) {
                $peminjaman->total_denda = $totalDenda;
                $peminjaman->save();
            }
        }
    }
} 