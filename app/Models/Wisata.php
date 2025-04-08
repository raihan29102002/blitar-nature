<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Wisata extends Model {
    use HasFactory;

    protected $table = 'wisatas';
    protected $fillable = ['nama', 'deskripsi', 'koordinat_x', 'koordinat_y', 'status_pengelolaan', 'harga_tiket', 'status_tiket'];

    public function kunjungans() {
        return $this->hasMany(Kunjungan::class);
    }
    public function media() {
        return $this->hasMany(Media::class);
    }
    public function fasilitas() {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_wisata')
            ->withTimestamps();
    }
    public function ratings() {
        return $this->hasMany(RatingFeedback::class);
    }
}