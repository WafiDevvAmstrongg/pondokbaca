<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $userId = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = 'user';
    public $is_active = true;

    public $showSuccessNotification = false;
    public $notificationMessage = '';

    protected $listeners = ['refreshUsers' => '$refresh'];

    protected function rules()
    {
        $rules = [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email|max:100',
            'role' => 'required|in:user,staff,admin',
            'is_active' => 'boolean'
        ];
        
        // Only add unique validation for email when creating or changing email
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        // Reset all fields when creating a new user
        $this->reset(['userId', 'name', 'email', 'password', 'role', 'is_active']);
        $this->showModal = true;
    }

    public function edit($userId)
    {
        $this->resetValidation();
        
        $user = User::findOrFail($userId);
        
        // Set property values directly
        $this->userId = $userId;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->is_active = $user->is_active;
        $this->password = ''; // Reset password field
        
        // Force Livewire to recognize the data update
        $this->dispatch('propertyUpdated');
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->userId) {
            $user = User::find($this->userId);
            $user->update($data);
             $this->notificationMessage = 'User berhasil diperbarui.';
        } else {
            User::create($data);
            $this->notificationMessage = 'User berhasil ditambahkan.';
        }

        $this->showSuccessNotification = true;
        
        // Dispatch event to auto-hide notification after 3 seconds
        $this->dispatch('hideSuccessNotification');

        $this->showModal = false;
        $this->reset(['userId', 'name', 'email', 'password', 'role', 'is_active']);
    }

    public function delete($userId)
    {
        $user = User::find($userId);
        if ($user->cover_img) {
            Storage::disk('public')->delete($user->profile_img);
        }
        $user->delete();
        
        // Show deletion notification
        $this->notificationMessage = 'User berhasil dihapus!';
        $this->showSuccessNotification = true;
        
        // Dispatch event to auto-hide notification after 3 seconds
        $this->dispatch('hideSuccessNotification');
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
                    ->paginate(10);

        return view('livewire.admin.users.index', compact('users'))->layout('layouts.admin');
    }
}