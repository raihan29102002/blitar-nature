<?php

namespace App\Livewire\Pages\Wisatawan;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
    use WithFileUploads;

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
            'foto_profil' => 'nullable|image|max:1024',
        ];

        if ($this->current_password || $this->new_password || $this->new_password_confirmation) {
            $rules['current_password'] = 'required';
            $rules['new_password'] = 'required|min:6|confirmed';
        }

        $messages = [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.string' => 'Nama harus berupa teks',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter',
            'email.required' => 'Alamat email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain',
            'alamat.string' => 'Alamat harus berupa teks',
            'alamat.max' => 'Alamat tidak boleh lebih dari 255 karakter',
            'foto_profil.image' => 'File harus berupa gambar (JPEG, PNG, JPG)',
            'foto_profil.max' => 'Ukuran gambar tidak boleh lebih dari 1MB',
            'current_password.required' => 'Password lama wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak sesuai',
        ];

        $validated = $this->validate($rules, $messages);

        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->alamat = $this->alamat;

        if ($this->foto_profil) {
            $upload = Cloudinary::uploadApi()->upload($this->foto_profil->getRealPath(), [
                'folder' => 'foto_profil'
            ]);
            $this->user->foto_profil = $upload['secure_url'];
        }

        if ($this->current_password && $this->new_password) {
            if (!Hash::check($this->current_password, $this->user->password)) {
                $this->addError('current_password', 'Password lama tidak sesuai');
                return;
            }

            $this->user->password = bcrypt($this->new_password);
            $this->current_password = $this->new_password = $this->new_password_confirmation = null;
        }

        $this->user->save();

        session()->flash('message', 'Profil berhasil diperbarui!');
    }

    public function render()
    {
        return view('livewire.pages.wisatawan.profil')
            ->layout('layouts.wisatawan');
    }
}