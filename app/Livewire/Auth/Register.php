<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $username = '';
    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'name' => 'required|min:3|max:100',
        'email' => 'required|email|unique:users,email|max:100',
        'username' => 'required|unique:users,username|min:3|max:50',
        'password' => 'required|min:8|confirmed',
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'role' => 'user',
            'is_active' => true
        ]);

        auth()->login($user);
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
    
}
