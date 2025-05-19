<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationToast extends Component
{
    public $message = '';
    public $type = 'success';

    protected $listeners = ['showToast' => 'displayToast'];
    public $trigger;

    public function displayToast($type = 'success', $message = 'Berhasil!')
    {
        $this->type = $type;
        $this->message = $message;

        $this->trigger = now()->timestamp;
    }
    public function mount()
    {
        if (session()->has('toast')) {
            $toast = session('toast');
            $this->type = $toast['type'];
            $this->message = $toast['message'];
            $this->trigger = now()->timestamp;
        }
    }

    public function render()
    {
        return view('livewire.notification-toast');
    }
}
