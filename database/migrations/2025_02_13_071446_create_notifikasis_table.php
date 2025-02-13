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
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_peminjaman')->nullable()->constrained('peminjamans')->onDelete('cascade');
            $table->text('message');
            $table->enum('tipe', [
                'peminjaman_dibuat',
                'peminjaman_diproses',
                'peminjaman_dikirim',
                'peminjaman_diterima',
                'peminjaman_ditolak',
                'peminjaman_dikembalikan',
                'due_reminder',
                'overdue_notice',
                'denda_notice'
            ]);
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            // Index
            $table->index('tipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
