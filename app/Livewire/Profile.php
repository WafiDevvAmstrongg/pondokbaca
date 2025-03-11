<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Profile extends Component
{
    use WithFileUploads;

    public $name, $email, $photo, $newPhoto;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->photo = $user->photo ?? null; // Pastikan ada kolom 'photo' di tabel users
    }

    public function updatedNewPhoto()
    {
        $this->validate([
            'newPhoto' => 'image|max:1024', // Maksimal 1MB
        ]);
    }

    public function updateProfile()
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = User::find(Auth::id()); // Pastikan mengambil model Eloquent

        if ($user) {
            // Jika ada foto baru diunggah, simpan ke storage
            if ($this->newPhoto) {
                $photoPath = $this->newPhoto->store('profile-photos', 'public');
                $user->photo = $photoPath;
            }

            // Update data user
            $user->update([
                'name'  => $this->name,
                'email' => $this->email,
                'photo' => $user->photo,
            ]);

            session()->flash('message', 'Profil berhasil diperbarui!');
        } else {
            session()->flash('message', 'User tidak ditemukan.');
        }
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
