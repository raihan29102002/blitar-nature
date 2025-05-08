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

    public function render()
    {
        $reviews = RatingFeedbackModel::with('wisata', 'user') // Mengambil data wisata dan user
        ->orderByDesc('created_at') // Mengurutkan berdasarkan waktu pembuatan review
        ->paginate(10);
        return view('livewire.pages.admin.rating-feedback', [
            'reviews' => $reviews,
        ])->layout('layouts.admin');;
    }

    public function respon($id)
    {
        $this->selectedReviewId = $id;
        $this->responText = '';  // Reset input teks setiap kali klik tombol respon
        $this->editMode = true;   // Tampilkan modal respon
    }

    public function simpanRespon()
    {
        // Validasi input respon admin
        $this->validate([
            'responText' => 'required|string|max:1000',
        ]);

        // Cari review berdasarkan ID
        $review = RatingFeedbackModel::find($this->selectedReviewId);
        
        // Simpan respon admin
        $review->respon_admin = $this->responText;
        $review->save();

        // Tampilkan pesan sukses
        session()->flash('message', 'Respon berhasil disimpan.');

        // Tutup modal setelah respon disimpan
        $this->editMode = false;
    }

    public function closeModal()
    {
        $this->editMode = false; // Tutup modal tanpa simpan
    }
}