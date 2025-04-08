<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model {
    use HasFactory;

    protected $fillable = ['wisata_id', 'url', 'type'];

    public function wisata() {
        return $this->belongsTo(Wisata::class);
    }
}