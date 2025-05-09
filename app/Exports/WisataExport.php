<?php

namespace App\Exports;

use App\Models\Wisata;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WisataExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Wisata::with('fasilitas')->get()->map(function ($item) {
            return [
                'nama' => $item->nama,
                'deskripsi' => $item->deskripsi,
                'kategori' => $item->kategori,
                'harga_tiket' => $item->harga_tiket,
                'status_tiket' => $item->status_tiket,
                'status_pengelola' => $item->status_pengelola,
                'koordinat_x' => $item->koordinat_x,
                'koordinat_y' => $item->koordinat_y,
                'fasilitas' => $item->fasilitas->pluck('nama_fasilitas')->implode(', ') ?: 'Tidak ada fasilitas',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Deskripsi',
            'Kategori',
            'Harga Tiket',
            'Status Tiket',
            'Status Pengelola',
            'Koordinat X',
            'Koordinat Y',
            'Fasilitas',
        ];
    }
}
