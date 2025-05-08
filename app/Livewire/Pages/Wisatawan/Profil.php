<?php

namespace App\Livewire\Pages\Wisatawan;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class Profil extends Component
{
    public $user;
    public $name;
    public $email;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $this->user = Auth::user()->load('ratings.wisata');
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    public function updateProfile()
    {
        // Validasi dasar untuk nama dan email
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
        ];

        // Jika ada input password lama atau baru, validasi password juga
        if ($this->current_password || $this->new_password || $this->new_password_confirmation) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|min:6|confirmed';
        }

        $validated = $this->validate($rules);

        // Update nama dan email
        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        // Jika password diubah, cek dan simpan
        if ($this->current_password && $this->new_password) {
            if (!Hash::check($this->current_password, $this->user->password)) {
                $this->addError('current_password', 'Password lama tidak sesuai.');
                return;
            }

            $this->user->update([
                'password' => bcrypt($this->new_password),
            ]);

            // Reset field password
            $this->current_password = $this->new_password = $this->new_password_confirmation = null;
        }

        session()->flash('message', 'Profil berhasil diperbarui!');
    }
    public function render()
    {
        return view('livewire.pages.wisatawan.profil')
        ->layout('layouts.wisatawan');;
    }
}
