<?php

namespace App\Imports;

use App\Models\Wisata;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;

class WisataImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Wisata([
            'nama'         => $row[0],
            'koordinat_x'  => $row[1],
            'koordinat_y'  => $row[2],
            'harga_tiket'  => $row[3],
            'kategori'     => $row[4],
            'status_tiket' => $row[5] ?? 'aktif',
            'status_pengelola' => $row[6] ?? 'dinas',
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ]);
    }
}
