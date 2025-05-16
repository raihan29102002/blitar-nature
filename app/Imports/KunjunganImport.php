<?php

namespace App\Imports;

use App\Models\Kunjungan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class KunjunganImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['wisata_id'])) {
            return null;
        }
        return new Kunjungan([
            'wisata_id'  => $row['wisata_id'],
            'jumlah'     => $row['jumlah'],
            'bulan'      => $row['bulan'],
            'tahun'      => $row['tahun'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
