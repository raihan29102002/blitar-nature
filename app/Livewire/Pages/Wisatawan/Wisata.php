<?php

namespace App\Livewire\Pages\Wisatawan;

use Livewire\Component;
use App\Models\Wisata as WisataModel;
use App\Models\RatingFeedback;
use Illuminate\Support\Facades\Http;


class Wisata extends Component
{
    public $wisatas;
    public $userLat;
    public $userLng;
    public $filter = 'default';


    public function getResizedImageUrl($imageUrl)
    {
        if (!$imageUrl) {
            return null;
        }

        return cloudinary_thumb($imageUrl); 
    }
    function getAverageRating($id)
    {
        $averageRating = RatingFeedback::where('wisata_id', $id)
            ->avg('rating');

        return round($averageRating, 1); // Mengembalikan rata-rata rating
    }

    public function hitungJarakDenganGoogle($userLat, $userLng, $destLat, $destLng)
    {
        $apiKey = config('services.google_maps.key');

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$userLat},{$userLng}&destinations={$destLat},{$destLng}&key={$apiKey}";

        $response = Http::get($url);
        $data = $response->json();

        if (isset($data['rows'][0]['elements'][0]['distance'])) {
            return $data['rows'][0]['elements'][0]['distance']['value'] / 1000; // meter ke kilometer
        }

        return null;
    }

    protected $listeners = ['userLocationUpdated'];

    public function userLocationUpdated($lat, $lng)
    {
        $this->userLat = $lat;
        $this->userLng = $lng;
        $this->updateJarakWisata();
    }

    public function updatedUserLat()
    {
        $this->updateJarakWisata();
    }

    public function updatedUserLng()
    {
        $this->updateJarakWisata();
    }

    private function getTotalKunjungan()
    {
        return \DB::table('kunjungan')
            ->select('wisata_id', \DB::raw('SUM(jumlah) as total'))
            ->groupBy('wisata_id')
            ->pluck('total', 'wisata_id');
    }

    public function updateJarakWisata()
    {
        $this->wisatas = $this->prepareWisataData($this->wisatas);
    } 
    private function prepareWisataData($collection)
    {
        $totals = $this->getTotalKunjungan();

        return $collection->map(function ($wisata) use ($totals) {
            $wisata->resized_image_url = $this->getResizedImageUrl($wisata->media->first()?->url);
            $wisata->jarak = $this->userLat && $this->userLng
                ? $this->hitungJarakDenganGoogle($this->userLat, $this->userLng, $wisata->koordinat_x, $wisata->koordinat_y)
                : null;
            $wisata->rating = $this->getAverageRating($wisata->id);
            $wisata->total_pengunjung = $totals[$wisata->id] ?? 0;
            return $wisata;
        });
    }
    public function updatedFilter()
    {
        $this->loadFilteredWisata();
    }
    public function mount()
    {
        $this->loadFilteredWisata();
    }

    public function loadFilteredWisata()
    {
        if ($this->filter === 'rating') {
            $wisatas = WisataModel::with('media')->get()->sortByDesc(function ($wisata) {
                return $this->getAverageRating($wisata->id);
            });
        } elseif ($this->filter === 'lokasi' && $this->userLat && $this->userLng) {
            $wisatas = WisataModel::with('media')->get()->sortBy(function ($wisata) {
                return $this->hitungJarakDenganGoogle($this->userLat, $this->userLng, $wisata->koordinat_x, $wisata->koordinat_y);
            });
        } elseif ($this->filter === 'pengunjung') {
            $totals = $this->getTotalKunjungan();
            $wisatas = WisataModel::with('media')->get()->sortByDesc(function ($wisata) use ($totals) {
                return $totals[$wisata->id] ?? 0; // default 0 kalau tidak ada data kunjungan
            });
        } else {
            // DEFAULT: berdasarkan ID terkecil
            $wisatas = WisataModel::orderBy('id')->get();
        }

        $this->wisatas = $this->prepareWisataData($wisatas);
    }
    public function render()
    {
        return view('livewire.pages.wisatawan.wisata', [
            'wisatas' => $this->wisatas,
        ])->layout('layouts.wisatawan');
    }
}
