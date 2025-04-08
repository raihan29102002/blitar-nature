<?php

namespace App\Livewire\Pages\Admin\Dashboard;

use Livewire\Component;
use App\Models\Wisata;
use App\Models\RatingFeedback;
use Illuminate\Support\Facades\DB;

class Statistics extends Component
{
    public $totalWisata;
    public $wisataTerpopuler;

    public function mount()
    {
        // Hitung total wisata
        $this->totalWisata = Wisata::count();

        $wisataTerpopuler = Wisata::select('wisatas.id', 'wisatas.nama', DB::raw('AVG(rating_feedbacks.rating) as avg_rating'))
            ->join('rating_feedbacks', 'wisatas.id', '=', 'rating_feedbacks.wisata_id')
            ->groupBy('wisatas.id', 'wisatas.nama') // Tambahkan semua kolom yang dipilih ke GROUP BY
            ->orderByDesc('avg_rating')
            ->first();

    }

    public function render()
    {
        return view('livewire.pages.admin.dashboard.statistics');
    }
}