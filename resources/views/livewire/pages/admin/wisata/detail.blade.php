<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Detail Wisata</h2>

    <div class="bg-white p-4 rounded shadow-md">
        <p><strong>Nama:</strong> {{ $wisata->nama }}</p>
        <p><strong>Koordinat X:</strong> {{ $wisata->koordinat_x }}</p>
        <p><strong>Koordinat Y:</strong> {{ $wisata->koordinat_y }}</p>
        <p><strong>Harga Tiket:</strong> Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}</p>
        <p><strong>Deskripsi:</strong> {{ $wisata->deskripsi }}</p>
        <p><strong>Status Pengelolaan:</strong> {{ $wisata->status_pengelolaan }}</p>
        <p><strong>Status Tiket:</strong> {{ $wisata->status_tiket }}</p>
    </div>

    <a href="{{ route('admin.wisata') }}" class="mt-4 inline-block bg-gray-500 text-white px-4 py-2 rounded">
        🔙 Kembali
    </a>
</div>
