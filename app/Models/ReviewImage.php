<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReviewImage extends Model
{
    use HasFactory;

    protected $fillable = ['rating_feedback_id', 'image_path'];

    public function ratingFeedback()
    {
        return $this->belongsTo(RatingFeedback::class);
    }
}

