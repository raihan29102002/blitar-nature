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

    public function mount()
    {
        $this->wisatas = WisataModel::all()->map(function ($wisata) {
            $wisata->resized_image_url = $this->getResizedImageUrl($wisata->media->first()?->url);
            $wisata->jarak = null;
            return $wisata;
        });
    }

    public function render()
    {
        return view('livewire.pages.wisatawan.wisata', [
            'wisatas' => $this->wisatas,
        ])->layout('layouts.wisatawan');
    }

    public function getResizedImageUrl($imageUrl)
    {
        if (!$imageUrl) {
            return null;
        }

        return cloudinary_thumb($imageUrl); 
    }
    // function getAverageRating($wisataId)
    // {
    //     $averageRating = RatingFeedback::where('wisata_id', $wisataId)
    //         ->avg('rating'); // 'rating' adalah kolom nilai rating pada tabel RatingFeedback

    //     return round($averageRating, 1); // Mengembalikan rata-rata rating
    // }

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

    public function updateJarakWisata()
    {
        if ($this->userLat && $this->userLng) {
            $this->wisatas->map(function ($wisata) {
                $wisata->jarak = $this->hitungJarakDenganGoogle(
                    $this->userLat,
                    $this->userLng,
                    $wisata->koordinat_x,
                    $wisata->koordinat_y
                );
                return $wisata;
            });
        }
    } 
}
