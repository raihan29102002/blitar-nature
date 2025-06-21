<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Kunjungan extends Model {
    use HasFactory;
    protected $table = 'kunjungan';
    protected $fillable = ['wisata_id', 'jumlah', 'bulan', 'tahun'];
    
    public function getNamaBulanAttribute()
    {
        $bulan = (int) $this->bulan;
        $tahun = $this->tahun ?? now()->year;
        $tahun = (int) $tahun;
         return Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');
    }

    public function wisata() {
        return $this->belongsTo(Wisata::class);
    }
}