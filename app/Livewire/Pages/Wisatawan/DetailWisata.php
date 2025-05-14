<?php

namespace App\Livewire\Pages\Wisatawan;

use Livewire\Component;
use App\Models\Wisata;
use App\Models\RatingFeedback;
use Livewire\WithPagination;

class DetailWisata extends Component
{
    use WithPagination;
    public $wisata;
    public $rating = 0;
    public $feedback;
    public $averageRating;
    public $response_admin = [];
    protected $paginationTheme = 'tailwind';
    protected $queryString = ['page'];

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'feedback' => 'nullable|string|max:1000',
    ];

    public function mount($id)
    {
        $this->wisata = Wisata::with(['media', 'ratings.user', 'fasilitas'])->findOrFail($id);
        $this->calculateAverageRating();
    }

    protected function calculateAverageRating()
    {
        $this->averageRating = round($this->wisata->ratings->avg('rating') ?? 0, 1);
    }

    public function submitRating()
    {
        $this->validate();

        $existingRating = RatingFeedback::where('user_id', auth()->id())
            ->where('wisata_id', $this->wisata->id)
            ->first();

        if ($existingRating) {
            $this->addError('rating', 'Anda sudah memberikan feedback untuk wisata ini.');
            return;
        }

        try {
            RatingFeedback::create([
                'user_id' => auth()->id(),
                'wisata_id' => $this->wisata->id,
                'rating' => $this->rating,
                'feedback' => $this->feedback,
            ]);

            $this->wisata->refresh();
            $this->calculateAverageRating();

            $this->reset(['rating', 'feedback']);
            $this->resetErrorBag();

            $this->dispatch('rating-submitted');

            session()->flash('success', 'Terima kasih atas feedback Anda!');
        } catch (\Exception $e) {
            $this->addError('rating', 'Terjadi kesalahan saat menyimpan feedback.');
        }
    }

    public function submitAdminResponse($id)
    {
        $this->validate([
            'response_admin.'.$id => 'required|string|max:1000',
        ]);

        try {
            $feedback = RatingFeedback::findOrFail($id);
            $feedback->update([
                'response_admin' => $this->response_admin[$id]
            ]);

            session()->flash('success', 'Balasan berhasil dikirim.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mengirim balasan.');
        }
    }

    public function render()
    {
        $feedbacks = RatingFeedback::with('user')
        ->where('wisata_id', $this->wisata->id)
        ->latest()
        ->paginate(5);
        return view('livewire.pages.wisatawan.detail-wisata', [
            'feedbacks' => $feedbacks
        ])->layout('layouts.wisatawan');
    }
}