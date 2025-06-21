<?php

namespace App\Livewire\Pages\Wisatawan;

use Livewire\Component;
use App\Models\Wisata;
use App\Models\RatingFeedback;
use App\Models\ReviewImage;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Validator;

class DetailWisata extends Component
{
    use WithPagination, WithFileUploads;

    public $wisata;
    public $rating = 0;
    public $feedback;
    public $averageRating;
    public $response_admin = [];
    public $images = [];

    protected $paginationTheme = 'tailwind';
    protected $queryString = ['page'];

    protected $rules = [
    'rating' => 'required|integer|min:1|max:5',
    'feedback' => 'required|string|max:1000',
    'images' => 'max:5',
    'images.*' => 'nullable|image|max:2048',
    ];
    protected $messages = [
        'rating.required' => 'Rating wajib diisi',
        'rating.min' => 'Rating minimal 1 bintang',
        'rating.max' => 'Rating maksimal 5 bintang',
        'feedback.max' => 'Komentar maksimal 1000 karakter',
        'images.*.image' => 'File harus berupa gambar (JPEG, PNG, JPG)',
        'images.*.max' => 'Ukuran gambar maksimal 2MB',
    ];

    public function mount($slug)
    {
        $this->wisata = Wisata::with(['media', 'ratings.user', 'fasilitas'])->where('slug', $slug)->firstOrFail();
        $this->calculateAverageRating();
    }

    protected function calculateAverageRating()
    {
        $this->averageRating = round($this->wisata->ratings->avg('rating') ?? 0, 1);
    }

    public function submitRating()
    {
        $data = [
            'rating' => $this->rating,
            'feedback' => $this->feedback,
            'images' => $this->images,
        ];

        $validator = Validator::make($data, $this->rules, $this->messages);
        if ($validator->fails()) {
            $this->setErrorBag($validator->errors());
            return;
        }

        $existingRating = RatingFeedback::where('user_id', auth()->id())
            ->where('wisata_id', $this->wisata->id)
            ->first();

        if ($existingRating) {
            $this->addError('rating', 'Anda sudah memberikan feedback untuk wisata ini.');
            session()->flash('toast', [
                'type' => 'warning',
                'message' => 'Anda sudah memberikan feedback untuk wisata ini.',
            ]);
            return;
        }

        try {
            $rating = RatingFeedback::create([
                'user_id' => auth()->id(),
                'wisata_id' => $this->wisata->id,
                'rating' => $this->rating,
                'feedback' => $this->feedback,
            ]);

            if ($this->images) {
                foreach ($this->images as $image) {
                    $imagePath = $image->getRealPath() ?? $image->getPathname();
                    if (!$imagePath || !file_exists($imagePath)) continue;

                    $extension = strtolower($image->getClientOriginalExtension());
                    $type = in_array($extension, ['jpg', 'png', 'jpeg', 'webp']) ? 'foto' : 'video';

                    try {
                        $upload = Cloudinary::uploadApi()->upload($image->getRealPath(), [
                            'folder' => 'review_images',
                            'resource_type' => 'image',
                            'quality' => 'auto:good',
                        ]);

                        ReviewImage::create([
                            'rating_feedback_id' => $rating->id,
                            'image_path' => $upload['secure_url'],
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Upload Gagal: '.$e->getMessage());
                        continue;
                    }
                }
            }

            $this->wisata->refresh();
            $this->calculateAverageRating();
            
            $this->resetForm();
            $this->dispatch('rating-submitted')->self();

            session()->flash('toast', [
                'type' => 'success',
                'message' => 'Terima kasih atas feedback Anda!',
            ]);
            $this->emit('map:refresh');
        } catch (\Exception $e) {
            session()->flash('toast', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan feedback.',
            ]);
        }
    }
    private function resetForm()
    {
        $this->rating = 0;
        $this->feedback = '';
        $this->images = [];
        $this->resetErrorBag();
        $this->dispatch('reset-rating');
        $this->dispatch('resetFileInput'); // Jika perlu mereset input file
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
            $this->reset(['response_admin']);

            session()->flash('toast', [
                'type' => 'success',
                'message' => 'Balasan berhasil dikirim.',
            ]);
        } catch (\Exception $e) {
            session()->flash('toast', [
                'type' => 'error',
                'message' => 'Gagal mengirim balasan.',
            ]);
        }
    }

    public function render()
    {
        $feedbacks = RatingFeedback::with('user', 'images')
            ->where('wisata_id', $this->wisata->id)
            ->latest()
            ->paginate(5);

        return view('livewire.pages.wisatawan.detail-wisata', [
            'feedbacks' => $feedbacks
        ])->layout('layouts.wisatawan');
    }
}
