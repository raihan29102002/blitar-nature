<?php

namespace App\Livewire\Pages\Wisatawan;

use Livewire\Component;
use App\Models\Wisata as WisataModel;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
        $this->resetPage();
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
    private function calculateHaversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo   = deg2rad($lat2);
        $lonTo   = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) *
            pow(sin($lonDelta / 2), 2)
        ));

        return round($earthRadius * $angle, 2); // dibulatkan 2 angka di belakang koma
    }


    private function loadFilteredWisata(): LengthAwarePaginator
    {
        $query = WisataModel::with(['media'])
            ->withAvg('ratings', 'rating')
            ->withSum('kunjungans', 'jumlah');

        if ($this->search) {
            $query->where('nama', 'like', '%' . $this->search . '%');
        }

        $query->withValidCoordinates();
        $collection = $query->get();

        if ($this->userLat && $this->userLng) {
            $collection->each(function ($wisata) {
                $wisata->jarak = $this->calculateHaversineDistance(
                    $this->userLat,
                    $this->userLng,
                    $wisata->koordinat_x,
                    $wisata->koordinat_y
                );
            });
        } else {
            $collection->each(function ($wisata) {
                $wisata->jarak = null;
            });
        }

        // Apply sorting based on filter
        $collection = $this->applySorting($collection);

        return $this->paginateCollection($this->prepareWisataData($collection));
    }

    private function applySorting(Collection $collection): Collection
    {
        return match ($this->filter) {
            'lokasi' => $collection->sortBy('jarak')->values(),
            'rating' => $collection->sortByDesc('ratings_avg_rating')->values(),
            'pengunjung' => $collection->sortByDesc('kunjungans_sum_jumlah')->values(),
            default => $collection,
        };
    }

    private function prepareWisataData(Collection $collection): Collection
    {
        return $collection->map(function ($wisata) {
            $wisata->resized_image_url = $this->getResizedImageUrl($wisata->media->first()?->url);
            $wisata->jarak = $wisata->jarak ?? null;
            $wisata->rating = $wisata->ratings_avg_rating ?? 0;
            $wisata->total_pengunjung = $wisata->kunjungans_sum_jumlah ?? 0;
        
            return $wisata; // tetap objek model, lebih aman
        });
    }

    private function paginateCollection(Collection $items): LengthAwarePaginator
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

    private function getResizedImageUrl(?string $imageUrl): ?string
    {
        return $imageUrl ? cloudinary_thumb($imageUrl) : null;
    }
}