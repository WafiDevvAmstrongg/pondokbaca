<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $userId = null;
    public $name = '';
    public $email = '';
    public $username = '';
    public $password = '';
    public $role = 'user';
    public $is_active = true;

    protected $rules = [
        'name' => 'required|min:3|max:100',
        'email' => 'required|email|max:100',
        'username' => 'required|min:3|max:50',
        'role' => 'required|in:user,staff,admin',
        'is_active' => 'boolean'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['userId', 'name', 'email', 'username', 'password', 'role', 'is_active']);
        $this->showModal = true;
    }

    public function edit($userId)
    {
        $this->userId = $userId;
        $user = User::find($userId);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->role = $user->role;
        $this->is_active = $user->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->userId) {
            $user = User::find($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'role' => $this->role,
                'is_active' => $this->is_active,
            ]);

            if ($this->password) {
                $user->update(['password' => Hash::make($this->password)]);
            }
        } else {
            $this->validate([
                'password' => 'required|min:8',
                'email' => 'unique:users,email',
                'username' => 'unique:users,username',
            ]);

            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'is_active' => $this->is_active,
            ]);
        }

        $this->showModal = false;
        $this->reset(['userId', 'name', 'email', 'username', 'password', 'role', 'is_active']);
    }

    public function toggleActive($userId)
    {
        $user = User::find($userId);
        $user->update(['is_active' => !$user->is_active]);
    }

    public function render()
    {
        $users = User::where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('username', 'like', '%'.$this->search.'%')
                    ->paginate(10);

        return view('livewire.admin.users.index', compact('users'))->layout('layouts.admin');;
    }
} 