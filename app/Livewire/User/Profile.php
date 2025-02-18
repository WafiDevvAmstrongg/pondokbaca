<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;

class Profile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $username;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $profile_img;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'username' => 'required|string|unique:users,username,' . auth()->id(),
            'profile_img' => 'nullable|image|max:1024'
        ]);

        $user = auth()->user();
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username
        ];

        if ($this->profile_img) {
            $data['profile_img'] = $this->profile_img->store('profile', 'public');
        }

        $user->update($data);

        session()->flash('message', 'Profile updated successfully.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed'
        ]);

        $user = auth()->user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'The current password is incorrect.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password)
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('message', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.user.profile')->layout('components.layouts.root', [
            'title' => 'My Profile'
        ]);
    }
} 