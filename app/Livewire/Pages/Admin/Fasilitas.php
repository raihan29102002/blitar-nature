<?php

namespace App\Livewire\Pages\Admin;

use Livewire\Component;
use App\Models\Fasilitas as MenuFasilitas;
use Livewire\WithPagination;

class Fasilitas extends Component
{
    public $modal = false;
    public $id, $nama_fasilitas;
    public $fasilitas = [];
    public $search = '';
    use WithPagination;
    
    public function updatedSearch()
    {
        $this->resetPage();
    }

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

        $this->dispatch('showToast', $this->id ? 'success' : 'success', $this->id
            ? 'Fasilitas berhasil diupdate.'
            : 'Fasilitas berhasil ditambahkan.');
        $this->closeModal();
        $this->loadFasilitas();
    }

    public function deleteFasilitas($id)
    {
        MenuFasilitas::findOrFail($id)->delete();
        $this->dispatch('showToast', 'success', 'Fasilitas berhasil dihapus.');
        $this->loadFasilitas();
    }
    

    public function render()
    {
        $fasilitasList = MenuFasilitas::where('nama_fasilitas', 'like', '%' . $this->search . '%')
        ->orderBy('nama_fasilitas')
        ->paginate(25);
        
        return view('livewire.pages.admin.fasilitas', [
            'fasilitasList' => $fasilitasList
        ])->layout('layouts.admin');
    }
}