<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'profile_img'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function peminjaman() {
        return $this->hasMany(Peminjaman::class, 'id_user');
    }

    public function handlePeminjaman() {
        return $this->hasMany(Peminjaman::class, 'id_staff');
    }

    public function suka() {
        return $this->hasMany(Suka::class, 'id_user');
    }

    public function hasSukaBook($bookId) {
        return $this->suka()->where('id_buku', $bookId)->exists();
    }

    public function ratings() {
        return $this->hasMany(Rating::class, 'id_user');
    }


    public function isAdmin() {
        return $this->role === 'admin';
    }

    public function isStaff() {
        return $this->role === 'staff';
    }
}
