<?php

namespace App\Livewire\Pages\Admin\Dashboard;

use Livewire\Component;
use App\Models\Wisata;
// use App\Models\RatingFeedback;
// use Illuminate\Support\Facades\DB;

class Statistics extends Component
{
    public $topWisata;

    public function mount()
    {
        $this->topWisata = Wisata::with(['media' => function ($query) {
            $query->whereNotNull('url');
            }])
            ->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->take(3)
            ->get()
            ->map(function ($item) {
                $item->average_rating = round($item->ratings_avg_rating, 1);
                $item->thumbnail_url = $item->media->first()?->url ?? asset('img/gunung.jpg');
                return $item;
            });
    }

    public function render()
    {
        return view('livewire.pages.admin.dashboard.statistics');
    }
}