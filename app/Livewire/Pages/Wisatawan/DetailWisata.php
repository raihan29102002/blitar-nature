<?php

namespace App\Livewire\Pages\Wisatawan;

use Livewire\Component;
use App\Models\Wisata;
use App\Models\RatingFeedback;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Http;


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

        // Panggil scraping saat halaman dimuat
        $this->getGoogleReviews();
    }

    protected function calculateAverageRating()
    {
        $this->averageRating = round($this->wisata->ratings->avg('rating') ?? 0, 1);
    }
    public function getGoogleReviews()
    {
        $apiKey = env('GOOGLE_MAPS_KEY');
        $namaTempat = $this->wisata->nama . ' Blitar';

        $searchResponse = Http::get("https://maps.googleapis.com/maps/api/place/findplacefromtext/json", [
            'input' => $namaTempat,
            'inputtype' => 'textquery',
            'fields' => 'place_id',
            'key' => $apiKey,
        ]);

        $placeId = $searchResponse['candidates'][0]['place_id'] ?? null;
        if (!$placeId) return;

        $detailResponse = Http::get("https://maps.googleapis.com/maps/api/place/details/json", [
            'place_id' => $placeId,
            'fields' => 'reviews',
            'key' => $apiKey,
        ]);


        $reviews = $detailResponse['result']['reviews'] ?? [];
        dd($reviews);

        $googleUser = User::find(9);
        if (!$googleUser) {
            // Kalau user ID 9 belum ada, bisa buat dulu
            $googleUser = User::create([
                'id' => 9,
                'name' => 'Google Reviewer',
                'email' => 'google@review.com',
                'password' => bcrypt('secret'), // password default
            ]);
        }

        RatingFeedback::where('user_id', $googleUser->id)
            ->where('wisata_id', $this->wisata->id)
            ->delete();

        foreach ($reviews as $review) {
            RatingFeedback::create([
                'user_id' => $googleUser->id,
                'wisata_id' => $this->wisata->id,
                'rating' => $review['rating'],
                'feedback' => $review['text'] ?? 'Tidak ada komentar.',
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Refresh data
        $this->wisata->refresh();
        $this->calculateAverageRating();
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
            'response_admin.' . $id => 'required|string|max:1000',
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
