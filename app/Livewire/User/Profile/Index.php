<?php

namespace App\Livewire\User\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithFileUploads;

    public $user;
    public $name;
    public $email;
    public $profile_img;
    public $newProfileImage;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    
    // Notification variables
    public $showProfileNotification = false;
    public $showPasswordNotification = false;

    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->profile_img = $this->user->profile_img;
    }

    public function updateProfile()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('users')->ignore($this->user->id)],
            'newProfileImage' => ['nullable', 'image', 'max:1024'], // 1MB Max
        ]);

        if ($this->newProfileImage) {
            // Delete old image if exists
            if ($this->user->profile_img && Storage::exists('public/profiles/' . $this->user->profile_img)) {
                Storage::delete('public/profiles/' . $this->user->profile_img);
            }

            // Store new image
            $imageName = time() . '_' . $this->newProfileImage->getClientOriginalName();
            $this->newProfileImage->storeAs('public/profiles', $imageName);
            $this->user->profile_img = $imageName;
            $this->profile_img = $imageName;
            $this->reset('newProfileImage');
        }

        $this->user->name = $validated['name'];
        $this->user->email = $validated['email'];
        $this->user->save();

        // Show notification
        $this->showProfileNotification = true;
        
        // Hide notification after 3 seconds
        $this->dispatch('hideProfileNotification');

        // Also show SweetAlert notification
        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Profil anda telah diperbarui.',
            'icon' => 'success',
        ]);
    }

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

        $this->user->password = Hash::make($validated['new_password']);
        $this->user->save();

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        // Show notification
        $this->showPasswordNotification = true;
        
        // Hide notification after 3 seconds
        $this->dispatch('hidePasswordNotification');

        // Also show SweetAlert notification
        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Password anda telah diperbarui.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.user.profile.index')->layout('layouts.user');
    }
}