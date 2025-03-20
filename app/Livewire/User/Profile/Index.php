<?php

// 📌 Namespace untuk menentukan lokasi class ini dalam struktur Livewire User Profile
namespace App\Livewire\User\Profile;

// 📌 Mengimpor fitur yang dibutuhkan
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Index extends Component
{
    // 📌 Menggunakan fitur upload file dari Livewire
    use WithFileUploads;

    // 📌 Variabel untuk menyimpan data pengguna & input form
    public $user; // Data pengguna yang sedang login
    public $name; // Nama pengguna
    public $email; // Email pengguna
    public $profile_img; // Gambar profil yang saat ini tersimpan
    public $newProfileImage; // Gambar profil yang akan diunggah
    public $current_password; // Password lama pengguna
    public $new_password; // Password baru
    public $new_password_confirmation; // Konfirmasi password baru
    
    // 📌 Variabel untuk menampilkan notifikasi
    public $showProfileNotification = false; // Notifikasi update profil
    public $showPasswordNotification = false; // Notifikasi update password

    /**
     * 📌 FUNGSI MOUNT
     * - Memuat data pengguna saat komponen pertama kali diakses.
     */
    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->profile_img = $this->user->profile_img;
    }

    /**
     * 📌 FUNGSI UPDATE PROFIL PENGGUNA
     * - Memvalidasi input data.
     * - Mengupdate nama, email, dan foto profil pengguna.
     * - Menampilkan notifikasi sukses setelah update berhasil.
     */
    public function updateProfile()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('users')->ignore($this->user->id)],
            'newProfileImage' => ['nullable', 'image', 'max:1024'], // 1MB Max
        ]);

        // 📌 Jika ada gambar baru yang diunggah
        if ($this->newProfileImage) {
            // 🔹 Hapus gambar lama jika ada
            if ($this->user->profile_img && Storage::exists('public/profiles/' . $this->user->profile_img)) {
                Storage::delete('public/profiles/' . $this->user->profile_img);
            }

            // 🔹 Simpan gambar baru
            $imageName = time() . '_' . $this->newProfileImage->getClientOriginalName();
            $this->newProfileImage->storeAs('public/profiles', $imageName);
            $this->user->profile_img = $imageName;
            $this->profile_img = $imageName;
            $this->reset('newProfileImage');
        }

        // 🔹 Perbarui data pengguna
        $this->user->name = $validated['name'];
        $this->user->email = $validated['email'];
        $this->user->save();

        // 🔹 Tampilkan notifikasi sukses
        $this->showProfileNotification = true;
        $this->dispatch('hideProfileNotification');

        // 🔹 Tampilkan alert SweetAlert
        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Profil anda telah diperbarui.',
            'icon' => 'success',
        ]);
    }

    /**
     * 📌 FUNGSI UPDATE PASSWORD
     * - Memvalidasi password lama dan password baru.
     * - Menyimpan password baru setelah lolos validasi.
     * - Menampilkan notifikasi sukses setelah update berhasil.
     */
    public function updatePassword()
    {
        $validated = $this->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, $this->user->password)) {
                    $fail('Password saat ini tidak cocok.');
                }
            }],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ]);

        // 🔹 Simpan password baru
        $this->user->password = Hash::make($validated['new_password']);
        $this->user->save();

        // 🔹 Reset input password
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        // 🔹 Tampilkan notifikasi sukses
        $this->showPasswordNotification = true;
        $this->dispatch('hidePasswordNotification');

        // 🔹 Tampilkan alert SweetAlert
        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Password anda telah diperbarui.',
            'icon' => 'success',
        ]);
    }

    /**
     * 📌 MENAMPILKAN TAMPILAN PROFIL
     */
    public function render()
    {
        return view('livewire.user.profile.index')->layout('layouts.user');
    }
}
