<?php

namespace App\Livewire\Pages\Admin;

use Livewire\Component;
use App\Models\Fasilitas as MenuFasilitas;

class Fasilitas extends Component
{
    protected $middleware = ['auth', 'role:admin'];
    public $modal = false;
    public $id, $nama_fasilitas;
    public $fasilitas = [];

    public function mount()
    {
        $this->loadFasilitas();
    }

    public function loadFasilitas()
    {
        $this->fasilitas = MenuFasilitas::orderBy('nama_fasilitas')->get();
    }

    public function openModal($id = null)
    {
        $this->modal = true;
        if ($id) {
            $fasilitas = MenuFasilitas::findOrFail($id);
            $this->id = $fasilitas->id;
            $this->nama_fasilitas = $fasilitas->nama_fasilitas;
        } else {
            $this->reset(['id', 'nama_fasilitas']);
        }
    }

    public function closeModal()
    {
        $this->modal = false;
        $this->reset(['id', 'nama_fasilitas']);
    }

    public function save()
    {
        $this->validate([
            'nama_fasilitas' => 'required|string|max:255',
        ]);

        MenuFasilitas::updateOrCreate(
            ['id' => $this->id],
            ['nama_fasilitas' => $this->nama_fasilitas]
        );

        session()->flash('message', 'Fasilitas berhasil disimpan!');
        $this->closeModal();
        $this->loadFasilitas();
    }

    public function deleteFasilitas($id)
    {
        MenuFasilitas::findOrFail($id)->delete();
        session()->flash('message', 'Fasilitas berhasil dihapus.');
        $this->loadFasilitas();
    }
    

    public function render()
    {
        return view('livewire.pages.admin.fasilitas')
        ->layout('layouts.admin');
    }
}