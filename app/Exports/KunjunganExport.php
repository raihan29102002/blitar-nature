<?php

namespace App\Exports;

use App\Models\Kunjungan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KunjunganExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Kunjungan::with('wisata')
            ->get()
            ->map(function ($item) {
                return [
                    'nama_wisata' => $item->wisata->nama ?? '-',
                    'jumlah_pengunjung' => $item->jumlah,
                    'bulan' => $item->nama_bulan,
                    'tahun' => $item->tahun,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Wisata',
            'Jumlah Pengunjung',
            'Bulan',
            'Tahun',
        ];
    }
}
