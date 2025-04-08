<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fasilitas extends Model {
    use HasFactory;

    protected $fillable = ['nama_fasilitas'];

    public function wisata() {
        return $this->belongsToMany(Wisata::class, 'fasilitas_wisata');
    }
}