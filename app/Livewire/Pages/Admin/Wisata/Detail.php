<?php

namespace App\Livewire\Pages\Admin\Wisata;

use Livewire\Component;
use App\Models\Wisata;
use App\Models\RatingFeedback;

class Detail extends Component
{
    public $wisata;
    public $rating = 0;
    public $averageRating;
    protected function calculateAverageRating()
    {
        $this->averageRating = round($this->wisata->ratings->avg('rating') ?? 0, 1);
    }

    public function mount($id)
    {
        $this->wisata = Wisata::with(['media', 'ratings.user', 'fasilitas'])->findOrFail($id);
        $this->calculateAverageRating();
    }
    protected $layout = 'layouts.admin';

    public function render()
    {
        return view('livewire.pages.admin.wisata.detail')
            ->layout('layouts.admin'); 
    }
    public function deleteWisata($id)
    {
        WisataModel::find($id)->delete();
        session()->flash('message', 'Wisata berhasil dihapus.');
    }
}