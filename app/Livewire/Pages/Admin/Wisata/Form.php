<?php

namespace App\Livewire\Pages\Admin\Wisata;

use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\Wisata;
use App\Models\Fasilitas;
use App\Models\Media;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class Form extends Component
{
    use WithFileUploads;

    public $wisataId, $nama, $deskripsi, $koordinat_x, $koordinat_y, $harga_tiket, $status_pengelolaan, $status_tiket, $link_informasi;
    public $kategori;
    public $selectedFasilitas = [];
    public $mediaFiles = [];
    protected $middleware = ['auth', 'role:admin'];

    public $showHargaTiket = false;
    public $mediaLama = [];
    public $uploadError;
    public $mapCenter = [-8.0955, 112.1686];

    // Pesan validasi dalam bahasa Indonesia
    protected $messages = [
        // Validasi nama wisata
        'nama.required' => 'Nama wisata wajib diisi',
        'nama.string' => 'Nama wisata harus berupa teks',
        'nama.max' => 'Nama wisata maksimal 255 karakter',
        
        // Validasi deskripsi
        'deskripsi.string' => 'Deskripsi harus berupa teks',
        
        // Validasi koordinat
        'koordinat_x.required' => 'Koordinat X wajib diisi',
        'koordinat_x.numeric' => 'Koordinat X harus berupa angka',
        'koordinat_y.required' => 'Koordinat Y wajib diisi',
        'koordinat_y.numeric' => 'Koordinat Y harus berupa angka',
        
        // Validasi kategori
        'kategori.required' => 'Kategori wisata wajib diisi',
        
        // Validasi harga tiket
        'harga_tiket.required' => 'Harga tiket wajib diisi untuk wisata berbayar',
        'harga_tiket.numeric' => 'Harga tiket harus berupa angka',
        
        // Validasi status pengelolaan
        'status_pengelolaan.required' => 'Status pengelolaan wajib dipilih',
        
        // Validasi status tiket
        'status_tiket.required' => 'Status tiket wajib dipilih',
        'status_tiket.in' => 'Status tiket harus berbayar atau gratis',
        
        'link_informasi.url' => 'Format link informasi tidak valid',

        'mediaFiles.*.file' => 'File harus berupa file yang valid',
        'mediaFiles.*.mimes' => 'Format file harus JPG, PNG, JPEG, WEBP, MP4, MOV, atau AVI',
        'mediaFiles.*.max' => 'Ukuran file maksimal 20MB',
    ];

    public function checkTiketStatus()
    {
        if ($this->status_tiket === 'berbayar') {
            $this->showHargaTiket = true;
            $this->harga_tiket = null;
        } else {
            $this->showHargaTiket = false;
            $this->harga_tiket = 0;
        }
    }

    public function mount($id = null)
    {
        if ($id) {
            $wisata = Wisata::with('media')->findOrFail($id);
            $this->wisataId = $wisata->id;
            $this->nama = $wisata->nama;
            $this->deskripsi = $wisata->deskripsi;
            $this->koordinat_x = $wisata->koordinat_x;
            $this->koordinat_y = $wisata->koordinat_y;
            $this->mapCenter = [$wisata->koordinat_y, $wisata->koordinat_x];
            $this->kategori = $wisata->kategori;
            $this->harga_tiket = $wisata->harga_tiket;
            $this->status_pengelolaan = $wisata->status_pengelolaan;
            $this->status_tiket = $wisata->status_tiket;
            $this->selectedFasilitas = $wisata->fasilitas->pluck('id')->toArray();
            $this->checkTiketStatus();
            $this->harga_tiket = $wisata->harga_tiket;
            $this->link_informasi = $wisata->link_informasi;
            $this->mediaLama = $wisata->media->map(function ($media) {
                return [
                    'url' => $media->url,
                    'type' => $media->type,
                ];
            })->toArray();
        }
    }
    public function setLocation($lat, $lng)
    {
        $this->koordinat_x = $lat;
        $this->koordinat_y = $lng;
    }

    public function save()
    {
        $this->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'koordinat_x' => 'required|numeric',
            'koordinat_y' => 'required|numeric',
            'kategori' => 'required|string|max:100',
            'harga_tiket' => $this->status_tiket === 'berbayar' ? 'required|numeric|min:1' : 'nullable|numeric',
            'status_pengelolaan' => 'required',
            'status_tiket' => 'required|in:berbayar,gratis',
            'link_informasi' => 'nullable|url',
            'mediaFiles' => 'nullable|array',
            'mediaFiles.*' => 'file|mimes:jpg,png,jpeg,webp,mp4,mov,avi|max:20480',
        ]);

        $wisata = Wisata::updateOrCreate(['id' => $this->wisataId], [
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'koordinat_x' => $this->koordinat_x,
            'koordinat_y' => $this->koordinat_y,
            'kategori' => $this->kategori,
            'harga_tiket' => $this->status_tiket === 'gratis' ? 0 : $this->harga_tiket,
            'status_pengelolaan' => $this->status_pengelolaan,
            'status_tiket' => $this->status_tiket,
            'link_informasi' => $this->link_informasi,
        ]);

        $wisata->fasilitas()->sync($this->selectedFasilitas);

        if (!empty($this->mediaFiles)) {
            foreach ($this->mediaFiles as $file) {
                if (!$file || !$file->getRealPath()) {
                    continue;
                }

                $extension = strtolower($file->getClientOriginalExtension());
                $type = in_array($extension, ['jpg', 'png', 'jpeg', 'webp']) ? 'foto' : 'video';

                try {
                    $upload = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                        'folder' => 'media_wisata',
                        'resource_type' => 'auto',
                        'quality' => 'auto:good',
                    ]);

                    Media::create([
                        'wisata_id' => $wisata->id,
                        'url' => $upload['secure_url'],
                        'type' => $type
                    ]);
                } catch (\Exception $e) {
                    $this->uploadError = "Gagal mengupload file: " . $e->getMessage();
                    continue;
                }
            }
        }
        $this->reset('mediaFiles');

        return redirect()->route('admin.wisata')->with('toast', [
            'type' => 'success',
            'message' => 'Data Wisata berhasil disimpan!',
        ]);
    }

    protected $layout = 'layouts.admin';

    public function render()
    {
        return view('livewire.pages.admin.wisata.form', [
            'allFasilitas' => Fasilitas::all(),
        ])->layout('layouts.admin');
    }
}