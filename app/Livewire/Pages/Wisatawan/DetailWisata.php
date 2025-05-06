<?php

namespace App\Livewire\Pages\Wisatawan;

use Livewire\Component;
use App\Models\Wisata;
use App\Models\Media;
use App\Models\RatingFeedback;

class DetailWisata extends Component
{
    public $wisata;
    public $rating = 0; // Inisialisasi dengan 0
    public $feedback;
    public $averageRating;
    public $response_admin = [];

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'feedback' => 'nullable|string|max:1000',
    ];

    public function mount($id)
    {
        $this->wisata = Wisata::with(['media', 'ratings.user'])->findOrFail($id);
        $this->calculateAverageRating();
    }

    protected function calculateAverageRating()
    {
        $this->averageRating = round($this->wisata->ratings->avg('rating') ?? 0, 1);
    }

    public function submitRating()
    {
        $this->validate();

        // Cek apakah user sudah memberikan rating sebelumnya
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

            // Refresh data wisata dengan ratings terbaru
            $this->wisata->refresh();
            $this->calculateAverageRating();

            // Reset form
            $this->reset(['rating', 'feedback']);
            $this->resetErrorBag();

            // Emit event untuk refresh komponen
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
        return view('livewire.pages.wisatawan.detail-wisata')
            ->layout('layouts.wisatawan');
    }
}