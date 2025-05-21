<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Wisata extends Model
{
    use HasFactory;

    protected $table = 'wisatas';
    protected $fillable = ['nama', 'slug', 'deskripsi', 'koordinat_x', 'koordinat_y', 'kategori', 'status_pengelolaan', 'harga_tiket', 'status_tiket'];
    protected $casts = [
        'koordinat_x' => 'float',
        'koordinat_y' => 'float',
    ];

    protected static function booted()
    {
        static::creating(function ($wisata) {
            $baseSlug = Str::slug($wisata->nama);
            $slug = $baseSlug;
            $counter = 1;

            while (Wisata::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $wisata->slug = $slug;
        });

        static::updating(function ($wisata) {
            if (empty($wisata->slug)) {
                $wisata->slug = Str::slug($wisata->nama);
            }
        });
    }

    public function scopeWithValidCoordinates($query)
    {
        return $query->whereNotNull('koordinat_x')
            ->whereNotNull('koordinat_y');
    }
    public function kunjungans()
    {
        return $this->hasMany(Kunjungan::class);
    }
    public function media()
    {
        return $this->hasMany(Media::class);
    }
    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_wisata', 'wisata_id', 'fasilitas_id')
            ->withTimestamps();
    }
    public function ratings()
    {
        return $this->hasMany(RatingFeedback::class);
    }
}
