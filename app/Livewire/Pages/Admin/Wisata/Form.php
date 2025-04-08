<?php

namespace App\Livewire\Pages\Admin\Wisata;

use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Wisata;
use App\Models\Fasilitas;
use App\Models\Media;

class Form extends Component
{
    use WithFileUploads;
    
    public $wisataId, $nama, $deskripsi, $koordinat_x, $koordinat_y, $harga_tiket, $status_pengelolaan, $status_tiket;
    public $selectedFasilitas = [];
    public $mediaFiles = []; // Perbaikan nama variabel
    protected $middleware = ['auth', 'role:admin'];
    
    public $showHargaTiket = false;

    public function checkTiketStatus()
    {
        if ($this->status_tiket === 'berbayar') {
            $this->showHargaTiket = true;
            $this->harga_tiket = null; // Biarkan user isi manual
        } else {
            $this->showHargaTiket = false;
            $this->harga_tiket = 0; // Langsung set harga ke 0 jika gratis
        }
    }

    public function mount($id = null)
    {
        if ($id) {
            $wisata = Wisata::findOrFail($id);
            $this->wisataId = $wisata->id;
            $this->nama = $wisata->nama;
            $this->deskripsi = $wisata->deskripsi;
            $this->koordinat_x = $wisata->koordinat_x;
            $this->koordinat_y = $wisata->koordinat_y;
            $this->harga_tiket = $wisata->harga_tiket;
            $this->status_pengelolaan = $wisata->status_pengelolaan;
            $this->status_tiket = $wisata->status_tiket;
            $this->selectedFasilitas = $wisata->fasilitas->pluck('id')->toArray();
        }
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'koordinat_x' => 'required|string',
            'koordinat_y' => 'required|string',
            'harga_tiket' => $this->status_tiket === 'berbayar' ? 'required|numeric|min:1' : 'nullable|numeric',
            'status_pengelolaan' => 'required',
            'status_tiket' => 'required|in:berbayar,gratis',
            'mediaFiles.*' => 'file|mimes:jpg,png,jpeg,webp,mp4,mov,avi|max:20480', // FIXED: gunakan mediaFiles.*
        ]);

        $wisata = Wisata::updateOrCreate(['id' => $this->wisataId], [
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'koordinat_x' => $this->koordinat_x,
            'koordinat_y' => $this->koordinat_y,
            'harga_tiket' => $this->status_tiket === 'gratis' ? 0 : $this->harga_tiket,
            'status_pengelolaan' => $this->status_pengelolaan,
            'status_tiket' => $this->status_tiket,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $wisata->fasilitas()->sync($this->selectedFasilitas);

        if ($this->mediaFiles) {
            foreach ($this->mediaFiles as $file) {
                $extension = $file->getClientOriginalExtension();
                $type = in_array($extension, ['jpg', 'png', 'jpeg', 'webp']) ? 'foto' : 'video';

                $path = $file->store('media', 'public');

                Media::create([
                    'wisata_id' => $wisata->id,
                    'url' => $path,
                    'type' => $type
                ]);
            }
        }

        return redirect()->route('admin.wisata')->with('message', 'Data berhasil disimpan!');

    }

    public function render()
    {
        return view('livewire.pages.admin.wisata.form', [
            'allFasilitas' => Fasilitas::all(),
        ])
        ->layout('layouts.admin');
    }
}