<?php

namespace App\Livewire;

use Livewire\Component;

class ToastRedirect extends Component
{
    public $message = '';
    public $type = 'success';
    public $show = false;

    public function mount()
    {
        if (session()->has('toast')) {
            $toast = session('toast');
            $this->type = $toast['type'] ?? 'success';
            $this->message = $toast['message'] ?? 'Berhasil!';
            $this->show = true;
        }
    }

    public function render()
    {
        return view('livewire.toast-redirect');
    }
}

