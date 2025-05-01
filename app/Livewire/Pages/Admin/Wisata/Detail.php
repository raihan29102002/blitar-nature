<?php

namespace App\Livewire\Pages\Admin\Wisata;

use Livewire\Component;
use App\Models\Wisata;

class Detail extends Component
{
    public $wisata;

    public function mount($id)
    {
        $this->wisata = Wisata::findOrFail($id);
    }
    protected $layout = 'layouts.admin';

    public function render()
    {
        return view('livewire.pages.admin.wisata.detail')
            ->layout('layouts.admin'); // Sesuaikan dengan layout admin-mu
    }
}