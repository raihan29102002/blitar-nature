<?php

namespace App\Livewire\Pages\Wisatawan;

use Livewire\Component;
use App\Models\Wisata;
use App\Models\Media;

class Dashboard extends Component
{
    private function getResizedImageUrl(?string $imageUrl): ?string
    {
        return $imageUrl ? cloudinary_thumb($imageUrl) : null;
    }

    public function render()
    {
        $wisataTerpopuler = Wisata::with(['media'])
            ->withSum('kunjungans', 'jumlah')
            ->orderByDesc('kunjungans_sum_jumlah')
            ->take(6)
            ->get();

        // $highlightWisata = $wisataTerpopuler->first();

        $highlightWisata = Wisata::with('media')
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->first();

        // Ambil 8 foto acak dari media (untuk galeri)
        $gridFotos = Media::inRandomOrder()
            ->whereHas('wisata') // hanya ambil media yang terhubung ke wisata
            ->limit(8)
            ->get();

        foreach ($gridFotos as $media) {
            $media->resized_image_url = $this->getResizedImageUrl($media->url);
        }

        return view('livewire.pages.wisatawan.dashboard', compact(
            'wisataTerpopuler',
            'highlightWisata',
            'gridFotos'
        ))->layout('layouts.home');
    }
}
