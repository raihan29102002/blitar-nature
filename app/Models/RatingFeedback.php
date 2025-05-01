<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingFeedback extends Model {
    use HasFactory;
    protected $table = 'rating_feedback';

    protected $fillable = ['wisata_id', 'user_id', 'rating', 'feedback', 'response_admin'];


    public function wisata() {
        return $this->belongsTo(Wisata::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}