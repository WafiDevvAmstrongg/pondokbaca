<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'bukus';

    protected $fillable = [
        'judul',
        'penulis',
        'isbn',
        'kategori',
        'deskripsi',
        'cover_img',
        'stok',
        'denda_harian',
        'penerbit',
        'tahun_terbit'
    ];

    protected $casts = [
        'stok' => 'integer',
        'denda_harian' => 'integer',
        'tahun_terbit' => 'integer'
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_buku');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'id_buku');
    }

    public function suka()
    {
        return $this->hasMany(Suka::class, 'id_buku');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'id_buku');
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }

    public function totalSuka()
    {
        return $this->suka()->count();
    }

    public function isAvailable()
    {
        return $this->stok > 0;
    }
}
