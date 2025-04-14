<?php

namespace App\Livewire\Pages\Admin\Dashboard;

use Livewire\Component;
use App\Models\Wisata;

class Map extends Component
{
    public $wisata;

    public function mount()
    {
        $this->wisata = Wisata::select('nama', 'koordinat_x', 'koordinat_y', 'kategori')->get();
    }

    public function render()
    {
        return view('livewire.pages.admin.dashboard.map');
    }
}