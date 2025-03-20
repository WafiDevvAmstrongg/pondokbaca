<?php

// 📌 Namespace untuk menentukan lokasi class ini dalam struktur Livewire Admin Users
namespace App\Livewire\Admin\Users;

// 📌 Mengimpor model User untuk interaksi dengan database
use App\Models\User;

// 📌 Mengimpor fitur Livewire untuk membuat komponen dinamis
use Livewire\Component;
use Livewire\WithPagination;

// 📌 Mengimpor Hash untuk enkripsi password
use Illuminate\Support\Facades\Hash;

// 📌 Mengimpor Storage untuk pengelolaan file (jika ada avatar pengguna)
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    // 📌 Menggunakan fitur paginasi Livewire
    use WithPagination;

    // 📌 Variabel untuk filter pencarian dan modal
    public $search = ''; // Kata kunci pencarian pengguna
    public $showModal = false; // Menampilkan modal tambah/edit pengguna
    public $userId = null; // ID pengguna yang sedang diedit
    public $name = ''; // Nama pengguna
    public $email = ''; // Email pengguna
    public $password = ''; // Password pengguna (hanya untuk pendaftaran dan reset)
    public $role = 'user'; // Peran pengguna (default: user)
    public $is_active = true; // Status keaktifan pengguna

    // 📌 Variabel untuk menampilkan notifikasi sukses
    public $showSuccessNotification = false;
    public $notificationMessage = '';

    // 📌 Mendaftarkan listener untuk memperbarui data pengguna
    protected $listeners = ['refreshUsers' => '$refresh'];

    /**
     * 📌 ATURAN VALIDASI INPUT
     * Menentukan validasi saat menyimpan data pengguna.
     */
    protected function rules()
    {
        $rules = [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|max:100',
            'role' => 'required|in:user,staff,admin',
            'is_active' => 'boolean'
        ];

        //  Validasi email unik hanya jika pengguna baru atau email diubah
        if (!$this->userId) {
            $rules['email'] = 'required|email|max:100|unique:users,email';
            $rules['password'] = 'required|min:8';
        } else {
            $user = User::find($this->userId);
            if ($user && $this->email !== $user->email) {
                $rules['email'] = 'required|email|max:100|unique:users,email';
            }
            if ($this->password) {
                $rules['password'] = 'min:8';
            }
        }

        return $rules;
    }

    /**
     * 📌 RESET PAGINASI SAAT PENCARIAN BERUBAH
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * 📌 MENAMPILKAN MODAL UNTUK TAMBAH PENGGUNA
     */
    public function create()
    {
        $this->reset(['userId', 'name', 'email', 'password', 'role', 'is_active']);
        $this->showModal = true;
    }

    /**
     * 📌 MENAMPILKAN MODAL UNTUK EDIT PENGGUNA
     */
    public function edit($userId)
    {
        $this->resetValidation();

        $user = User::findOrFail($userId);

        // 📌 Mengisi data pengguna ke dalam form
        $this->userId = $userId;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->is_active = $user->is_active;
        $this->password = ''; // Reset password field agar kosong

        // 📌 Memaksa Livewire untuk memperbarui UI
        $this->dispatch('propertyUpdated');

        $this->showModal = true;
    }

    /**
     * 📌 MENYIMPAN DATA PENGGUNA (TAMBAH / UPDATE)
     */
    public function save()
    {
        $this->validate(); // 📌 Melakukan validasi sebelum menyimpan

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
        ];

        // 📌 Enkripsi password jika ada input baru
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->userId) {
            // 📌 Update data pengguna jika sedang dalam mode edit
            $user = User::find($this->userId);
            $user->update($data);
            $this->notificationMessage = 'User berhasil diperbarui.';
        } else {
            // 📌 Tambah pengguna baru
            User::create($data);
            $this->notificationMessage = 'User berhasil ditambahkan.';
        }

        // 📌 Menampilkan notifikasi sukses
        $this->showSuccessNotification = true;
        $this->dispatch('hideSuccessNotification'); // Auto-hide notifikasi setelah 3 detik

        // 📌 Menutup modal & mereset form
        $this->showModal = false;
        $this->reset(['userId', 'name', 'email', 'password', 'role', 'is_active']);
    }

    /**
     * 📌 MENGHAPUS PENGGUNA
     */
    public function delete($userId)
    {
        $user = User::find($userId);

        // 📌 Menghapus avatar pengguna jika ada
        if ($user->profile_img) {
            Storage::disk('public')->delete($user->profile_img);
        }

        $user->delete();

        // 📌 Menampilkan notifikasi penghapusan
        $this->notificationMessage = 'User berhasil dihapus!';
        $this->showSuccessNotification = true;
        $this->dispatch('hideSuccessNotification'); // Auto-hide notifikasi setelah 3 detik
    }

    /**
     * 📌 MENGUBAH STATUS AKTIF PENGGUNA
     */
    public function toggleActive($userId)
    {
        $user = User::find($userId);
        $user->update(['is_active' => !$user->is_active]);
    }

    /**
     * 📌 MENAMPILKAN DATA PENGGUNA DENGAN PAGINASI & PENCARIAN
     */
    public function render()
    {
        $users = User::where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->paginate(10);

        return view('livewire.admin.users.index', compact('users'))->layout('layouts.admin');
    }
}
