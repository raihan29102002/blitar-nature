<?php

namespace App\Livewire\Pages\Wisatawan;

use Livewire\Component;
use App\Models\Wisata as WisataModel;
use App\Models\RatingFeedback;
use Illuminate\Support\Facades\Http;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class Wisata extends Component
{
    use WithPagination;

    public $userLat;
    public $userLng;
    public $filter = 'default';
    public $perPage = 8;
    public $search = '';

    protected $listeners = ['userLocationUpdated'];
    protected $updatesQueryString = ['search', 'filter'];

    public function updatingSearch()
    {
        $this->resetPage(); // reset ke halaman pertama saat pencarian berubah
    }

    public function mount()
    {
        $this->loadFilteredWisata();
    }

    public function render()
    {
        return view('livewire.pages.wisatawan.wisata', [
            'wisatas' => $this->loadFilteredWisata(),
        ])->layout('layouts.wisatawan');
    }

    public function userLocationUpdated($lat, $lng)
    {
        \Log::info('Koordinat yang diterima:', ['lat' => $lat, 'lng' => $lng]);

        $this->userLat = $lat;
        $this->userLng = $lng;

        if ($this->filter === 'lokasi') {
            $this->resetPage();
        }
    }


    public function updatedFilter()
    {
        $this->resetPage();
    }

    private function loadFilteredWisata(): LengthAwarePaginator
    {
        $query = WisataModel::with(['media'])
            ->withAvg('ratings', 'rating')
            ->withSum('kunjungans', 'jumlah');

        if ($this->search) {
                $query->where('nama', 'like', '%' . $this->search . '%');
        }

        if ($this->filter === 'lokasi' && $this->userLat && $this->userLng) {
            $query->withValidCoordinates();
            $collection = $query->get();
            $collection = $this->hitungJarakBatched($collection)->sortBy('jarak')->values();
            return $this->convertToPaginator($this->prepareWisataData($collection));
        }

        if ($this->filter === 'rating') {
            $query->orderByDesc('ratings_avg_rating');
        } elseif ($this->filter === 'pengunjung') {
            $query->orderByDesc('kunjungans_sum_jumlah');
        }

        return $this->convertToPaginator($this->prepareWisataData($query->get()));
    }

    private function prepareWisataData($collection)
    {
        return $collection->map(function ($wisata) {
            return (object) [
                ...$wisata->toArray(),
                'resized_image_url' => $this->getResizedImageUrl($wisata->media->first()?->url),
                'jarak' => $wisata->jarak ?? null,
                'rating' => $wisata->ratings_avg_rating ?? 0,
                'total_pengunjung' => $wisata->kunjungan_sum_jumlah ?? 0,
            ];
        });
    }

    private function convertToPaginator($items)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $items->slice(($currentPage - 1) * $this->perPage, $this->perPage)->values();

        return new LengthAwarePaginator(
            $currentItems,
            $items->count(),
            $this->perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    private function hitungJarakBatched($wisatas)
    {
        if (!$this->userLat || !$this->userLng) return $wisatas;

        // Filter wisata dengan koordinat valid
        $validWisatas = $wisatas->filter(function($w) {
            return !is_null($w->koordinat_x) && !is_null($w->koordinat_y);
        });

        if ($validWisatas->isEmpty()) {
            \Log::warning('Tidak ada wisata dengan koordinat valid untuk dihitung jarak');
            return $wisatas;
        }

        $apiKey = config('services.google_maps.key');
        $destinations = $validWisatas->map(fn($w) => "{$w->koordinat_x},{$w->koordinat_y}")->implode('|');

        $response = Http::get(
            "https://maps.googleapis.com/maps/api/distancematrix/json?" .
            "origins={$this->userLat},{$this->userLng}&" .
            "destinations={$destinations}&key={$apiKey}"
        );

        if (!$response->successful()) {
            \Log::error('Gagal mendapatkan jarak dari API', [
                'status' => $response->status(),
                'response' => $response->json()
            ]);
            return $wisatas;
        }

        $data = $response->json();
        $elements = $data['rows'][0]['elements'] ?? [];

        $validWisatas = $wisatas->filter(function($w) {
            // Pastikan format koordinat benar
            $lat = (float)$w->koordinat_x;
            $lng = (float)$w->koordinat_y;
            return $lat >= -90 && $lat <= 90 && 
                   $lng >= -180 && $lng <= 180;
        });
        \Log::debug('Sample coordinates:', [
            'wisata_1' => [
                'id' => $validWisatas->first()->id,
                'nama' => $validWisatas->first()->nama,
                'lat' => $validWisatas->first()->koordinat_x,
                'lng' => $validWisatas->first()->koordinat_y
            ],
            'user_location' => [
                'lat' => $this->userLat,
                'lng' => $this->userLng
            ]
        ]);

        return $wisatas;
    }


    private function getResizedImageUrl($imageUrl)
    {
        if (!$imageUrl) {
            return null;
        }

        return cloudinary_thumb($imageUrl);
    }
}
