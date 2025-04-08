<div class="bg-white p-4 rounded-lg shadow">
    <h2 class="text-lg font-semibold mb-3">Ringkasan Statistik</h2>
    <p>Total Wisata: {{ $totalWisata }}</p>
    @if ($wisataTerpopuler)
        <p>Wisata Terpopuler: {{ $wisataTerpopuler->nama }} (Rating: {{ number_format($wisataTerpopuler->avg_rating, 1) }})</p>
    @else
        <p>Belum ada rating wisata.</p>
    @endif
</div>