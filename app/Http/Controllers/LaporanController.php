<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Wisata;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function generateDashboardPdf(Request $request)
    {
        $totalWisata = Wisata::count();

        $wisataTerpopuler = Wisata::withCount('kunjungans')
            ->orderByDesc('kunjungans_count')
            ->first();

        $topWisata = Wisata::withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->take(5)
            ->get();

        $chartImage = $request->input('chart_image');
        $mapImage = $request->input('map_image');
        $totalKunjunganPertahun = DB::table('kunjungan')
            ->select('tahun', DB::raw('SUM(jumlah) as total'))
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->get();

        $kunjunganPerWisataPertahun = DB::table('kunjungan')
            ->join('wisatas', 'kunjungan.wisata_id', '=', 'wisatas.id')
            ->select('kunjungan.tahun', 'wisatas.nama', DB::raw('SUM(kunjungan.jumlah) as total'))
            ->groupBy('kunjungan.tahun', 'wisatas.nama')
            ->orderBy('kunjungan.tahun', 'desc')
            ->orderBy('total', 'desc')
            ->get();

        $data = [
            'totalWisata' => $totalWisata,
            'wisataTerpopuler' => $wisataTerpopuler,
            'topWisata' => $topWisata,
            'totalKunjunganPertahun' => $totalKunjunganPertahun,
            'kunjunganPerWisataPertahun' => $kunjunganPerWisataPertahun,
            'chartImage' => $chartImage,
            'mapImage' => $mapImage,
        ];

        $pdf = Pdf::loadView('export.statistik', $data)->setPaper('a4', 'portrait');

        return $pdf->download('laporan-dashboard.pdf');
    }
}
