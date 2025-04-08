<?php

namespace App\Livewire\Pages\Wisatawan;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.pages.wisatawan.dashboard')
        ->layout('layouts.app');
    }
}