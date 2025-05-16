<?php

namespace App\Livewire\Pages\Admin;

use Livewire\Component;
use app\Livewire\Actions\Logout;

class Dashboard extends Component
{
    public function logout()
    {
        (new Logout())();

        return redirect('/');
    }
    protected $middleware = ['auth', 'role:admin'];
    public function render()
    {
        return view('livewire.pages.admin.dashboard')
            ->layout('layouts.admin');
    }
}
