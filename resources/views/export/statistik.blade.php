<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Dashboard</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .section { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        h2 { margin-bottom: 5px; }
        img { max-width: 100%; height: auto; }
    </style>
</head>
<body>

    <h1>Laporan Dashboard Admin</h1>

    <div class="section">
        <h2>Statistik Umum</h2>
        <p>Total Wisata: {{ $totalWisata }}</p>
        <p>Wisata Terpopuler: {{ $wisataTerpopuler->nama ?? 'Data belum tersedia' }}</p>
    </div>

    <div class="section">
        <h2>Top 5 Wisata Berdasarkan Rating</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Wisata</th>
                    <th>Rating Rata-rata</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topWisata as $wisata)
                    <tr>
                        <td>{{ $wisata->nama }}</td>
                        <td>{{ number_format($wisata->rating_feedbacks_avg_rating, 1) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Grafik Kunjungan</h2>
        @if ($chartImage)
            <img src="{{ $chartImage }}" alt="Grafik Kunjungan">
        @else
            <p>Chart tidak tersedia</p>
        @endif
    </div>

    <div class="section">
        <h2 class="mt-4">Peta Wisata</h2>
        <img src="{{ $mapImage }}" style="max-width: 100%;">
    </div>

</body>
</html>
