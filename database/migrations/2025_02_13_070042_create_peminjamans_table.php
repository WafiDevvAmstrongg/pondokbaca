<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_buku')->constrained('bukus')->onDelete('cascade');
            $table->foreignId('id_staff')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nomor_resi')->nullable();
            $table->text('alamat_pengiriman');
            $table->text('catatan_pengiriman')->nullable();
            $table->datetime('tgl_peminjaman_diinginkan');
            $table->string('bukti_pengiriman')->nullable();
            $table->datetime('tgl_dikirim')->nullable();
            $table->datetime('tgl_kembali_rencana')->nullable();
            $table->datetime('tgl_kembali_aktual')->nullable();
            $table->enum('status', [
                'pending',
                'diproses',
                'dikirim',
                'dipinjam',
                'terlambat',
                'dikembalikan',
                'ditolak'
            ])->default('pending');
            $table->enum('metode_pengiriman', ['kurir', 'ambil_di_tempat'])->default('kurir');
            $table->integer('total_denda')->default(0);
            $table->string('bukti_pembayaran_denda')->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();

            // Index
            $table->index('status');
            $table->index('tgl_peminjaman_diinginkan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
