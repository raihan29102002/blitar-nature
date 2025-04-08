<?php

namespace App\Livewire\Pages\Admin;

use Livewire\Component;
use App\Models\RatingFeedback as RatingAdmin;
use Illuminate\Support\Facades\Auth;

class RatingFeedback extends Component
{
    public $wisataId, $rating, $feedback;
    public $reviews = [];
    public $responseAdmin = []; // Menyimpan input respons admin

    protected $rules = [
        'rating' => 'required|numeric|min:1|max:5',
        'feedback' => 'nullable|string|max:255',
    ];

    public function mount($wisataId)
    {
        $this->wisataId = $wisataId;
        $this->loadReviews();
    }

    public function loadReviews()
    {
        $this->reviews = RatingAdmin::where('wisata_id', $this->wisataId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Inisialisasi response admin untuk setiap feedback
        foreach ($this->reviews as $review) {
            $this->responseAdmin[$review->id] = $review->response_admin;
        }
    }

    public function submit()
    {
        $this->validate();

        // Cek apakah user sudah memberikan rating sebelumnya
        $existingRating = RatingAdmin::where('wisata_id', $this->wisataId)
            ->where('user_id', Auth::id())
            ->exists();

        if ($existingRating) {
            session()->flash('error', 'Anda sudah memberi rating.');
            return;
        }

        // Simpan rating ke database
        RatingAdmin::create([
            'wisata_id' => $this->wisataId,
            'user_id' => Auth::id(),
            'rating' => $this->rating,
            'feedback' => $this->feedback,
        ]);

        // Reset input
        $this->rating = null;
        $this->feedback = null;

        // Muat ulang ulasan
        $this->loadReviews();

        session()->flash('success', 'Rating dan ulasan berhasil dikirim!');
    }

    public function submitResponse($reviewId)
    {
        $review = RatingAdmin::find($reviewId);
        if ($review) {
            $review->response_admin = $this->responseAdmin[$reviewId] ?? null;
            $review->save();
            session()->flash('success', 'Tanggapan admin berhasil diperbarui.');
            $this->loadReviews(); 
        }
    }

    public function render()
    {
        $averageRating = RatingAdmin::where('wisata_id', $this->wisataId)->avg('rating');

        return view('livewire.pages.admin.rating-feedback', [
            'averageRating' => $averageRating,
        ]);
    }
}