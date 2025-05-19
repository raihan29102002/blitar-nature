<?php

namespace App\Livewire\Pages\Admin;

use Livewire\Component;
use App\Models\RatingFeedback as RatingFeedbackModel;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class RatingFeedback extends Component
{
    use WithPagination;
    protected $middleware = ['auth', 'role:admin'];
    public $responText;
    public $selectedReviewId;
    public $editMode = false;

    protected $listeners = ['simpanRespon'];
    public $search = '';

    protected $updatesQueryString = ['search']; 

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $reviews = RatingFeedbackModel::with('wisata', 'user')
        ->when($this->search, function ($query) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })->orWhereHas('wisata', function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%');
            });
        })
        ->orderByDesc('created_at')
        ->paginate(10);
        return view('livewire.pages.admin.rating-feedback', [
            'reviews' => $reviews,
        ])->layout('layouts.admin');;
    }

    public function respon($id)
    {
        $this->selectedReviewId = $id;
        $this->responText = '';
        $this->editMode = true;
    }

    public function simpanRespon()
    {
        $this->validate([
            'responText' => 'required|string|max:1000',
        ]);

        $review = RatingFeedbackModel::find($this->selectedReviewId);
        
        $review->response_admin = $this->responText;
        $review->save();

        $this->dispatch('showToast', 'success', 'Admin berhasil merespon review.');

        $this->editMode = false;
    }

    public function closeModal()
    {
        $this->editMode = false;
    }
}