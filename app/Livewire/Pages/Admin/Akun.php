<?php

namespace App\Livewire\Pages\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Akun extends Component
{
    use WithPagination;

    public $search = '';
    public $modal = false;

    public $userId, $name, $email, $password, $role;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'role' => 'required|in:admin,wisatawan',
        'password' => 'nullable|min:6',
    ];

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
                     ->orWhere('email', 'like', '%' . $this->search . '%')
                     ->paginate(10);

        return view('livewire.pages.admin.akun', [
            'users' => $users,
        ])->layout('layouts.admin');
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->reset(['userId', 'name', 'email', 'password', 'role']);
        $this->modal = true;

        if ($id) {
            $user = User::findOrFail($id);
            $this->userId = $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
        }
    }

    public function closeModal()
    {
        $this->modal = false;
    }

    public function save()
    {
        $this->validate();

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $user->name = $this->name;
            $user->email = $this->email;
            $user->role = $this->role;
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }
            $user->save();
            session()->flash('message', 'Akun berhasil diperbarui.');
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
                'password' => Hash::make($this->password),
            ]);
            session()->flash('message', 'Akun berhasil ditambahkan.');
        }

        $this->closeModal();
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message', 'Akun berhasil dihapus.');
    }
}
