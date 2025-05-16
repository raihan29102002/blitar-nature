<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Wisata;

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

        $data = [
            'totalWisata' => $totalWisata,
            'wisataTerpopuler' => $wisataTerpopuler,
            'topWisata' => $topWisata,
            'chartImage' => $chartImage,
            'mapImage' => $mapImage,
        ];

        $pdf = Pdf::loadView('export.statistik', $data)->setPaper('a4', 'portrait');

        return $pdf->download('laporan-dashboard.pdf');
    }
}
