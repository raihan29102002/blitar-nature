<?php

namespace App\Imports;

use App\Models\Kunjungan;
use Maatwebsite\Excel\Concerns\ToModel;
use Carbon\Carbon;

class KunjunganImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Kunjungan([
            'wisata_id' => $row[0],
            'jumlah'    => $row[1],
            'bulan'     => $row[2],
            'tahun'     => $row[3],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
