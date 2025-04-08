<?php

namespace App\Livewire\Pages\Admin;

use Livewire\Component;

class Akun extends Component
{
    protected $middleware = ['auth', 'role:admin'];
    public function render()
    {
        return view('livewire.pages.admin.akun')
        ->layout('layouts.admin');
    }
}