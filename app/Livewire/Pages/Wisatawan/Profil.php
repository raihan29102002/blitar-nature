<?php

namespace App\Livewire\Pages\Wisatawan;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Profil extends Component
{
    public $user;
    public $name;
    public $email;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $foto_profil;
    public $alamat;

    public function mount()
    {
        $this->user = Auth::user()->load('ratings.wisata');
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->alamat = $this->user->alamat;
    }

    public function updateProfile()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'alamat' => 'nullable|string|max:255',
            'foto_profil' => 'nullable|image|max:1024', // max 1MB
        ];

        if ($this->current_password || $this->new_password || $this->new_password_confirmation) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|min:6|confirmed';
        }

        $validated = $this->validate($rules);

        // Update data dasar
        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->alamat = $this->alamat;

        // Cek dan upload foto profil baru
        if ($this->foto_profil) {
            // Hapus foto lama jika ada
            if ($this->user->foto_profil && Storage::disk('public')->exists($this->user->foto_profil)) {
                Storage::disk('public')->delete($this->user->foto_profil);
            }

            $path = $this->foto_profil->store('foto_profil', 'public');
            $this->user->foto_profil = $path;
        }

        // Update password jika diisi
        if ($this->current_password && $this->new_password) {
            if (!Hash::check($this->current_password, $this->user->password)) {
                $this->addError('current_password', 'Password lama tidak sesuai.');
                return;
            }

            $this->user->password = bcrypt($this->new_password);

            // Reset field password
            $this->current_password = $this->new_password = $this->new_password_confirmation = null;
        }

        $this->user->save();

        session()->flash('message', 'Profil berhasil diperbarui!');
    }
    public function render()
    {
        return view('livewire.pages.wisatawan.profil')
        ->layout('layouts.wisatawan');;
    }
}
