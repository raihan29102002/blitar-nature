<?php

namespace App\Imports;

use App\Models\Wisata;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WisataImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nama'])) {
            return null;
        }
        return new Wisata([
            'nama'              => $row['nama'],
            'slug'              => Str::slug($row['nama']). '-' . uniqid(),
            'deskripsi'         => $row['deskripsi'],
            'kategori'          => $row['kategori'],
            'koordinat_x'       => $row['koordinat_x'],
            'koordinat_y'       => $row['koordinat_y'],
            'status_pengelola'  => $row['status_pengelolaan'],
            'harga_tiket'       => $row['harga_tiket'],
            'status_tiket'      => $row['status_tiket'],
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);
    }
}
