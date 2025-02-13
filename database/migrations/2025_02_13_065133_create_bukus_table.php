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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('isbn')->unique()->nullable();
            $table->enum('kategori', [
                'al-quran',
                'hadis',
                'fikih',
                'akidah',
                'sirah',
                'tafsir',
                'tarbiyah',
                'sejarah',
                'buku-anak',
                'novel',
                'lainnya',
            ]);
            $table->text('deskripsi')->nullable();
            $table->string('cover_img')->nullable();
            $table->integer('stok')->default(0);
            $table->integer('denda_harian')->default(0);
            $table->string('penerbit')->nullable();
            $table->string('tahun_terbit')->nullable();
            $table->timestamps();

            // Index
            $table->index('kategori');
            $table->index('judul');
            $table->index('penulis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
