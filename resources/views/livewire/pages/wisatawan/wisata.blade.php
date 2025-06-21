<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-10 text-center">Daftar Wisata Di Kabupaten Blitar</h1>
    
    <!-- Search Input -->
    <div class="flex justify-center mb-6">
        <input type="text" wire:model.live.debounce.500ms="search"
            class="w-full md:w-1/2 px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-teal-400"
            placeholder="Cari nama wisata...">
    </div>

    <!-- Filter Buttons -->
    <div class="flex justify-center mb-8 space-x-4">
        <button wire:click="$set('filter', 'rating')"
            class="px-4 py-2 rounded-lg text-sm font-semibold border {{ $filter === 'rating' ? 'bg-teal-600 text-white' : 'bg-white text-gray-800' }}">
            Rating Tertinggi
        </button>
        <button wire:click="$set('filter', 'lokasi')"
            class="px-4 py-2 rounded-lg text-sm font-semibold border {{ $filter === 'lokasi' ? 'bg-teal-600 text-white' : 'bg-white text-gray-800' }}">
            Lokasi Terdekat
        </button>
        <button wire:click="$set('filter', 'pengunjung')"
            class="px-4 py-2 rounded-lg text-sm font-semibold border {{ $filter === 'pengunjung' ? 'bg-teal-600 text-white' : 'bg-white text-gray-800' }}">
            Pengunjung Terbanyak
        </button>
    </div>

    <!-- Wisata List -->
    <div class="flex flex-wrap -mx-4 mb-24">
        @foreach ($wisatas as $wisata)
        <div class="w-full md:w-1/2 lg:w-1/4 px-4 mb-8">
            <div class="bg-white rounded-2xl shadow-md overflow-hidden flex flex-col h-full">
                
                <!-- Wisata Image -->
                @if ($wisata->resized_image_url)
                <img src="{{ $wisata->resized_image_url }}" alt="{{ $wisata->nama }}"
                    class="w-full h-48 object-cover rounded-xl mb-4">
                @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded-xl mb-4 text-gray-600">
                    Tidak ada gambar
                </div>
                @endif

                <!-- Wisata Info -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $wisata->nama }}</h2>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ \Illuminate\Support\Str::limit($wisata->deskripsi, 100) }}
                    </p>
                    <p class="text-sm text-gray-700 mt-auto">
                        {{ $wisata->harga_tiket == 0 ? 'Gratis' : 'Rp' . number_format($wisata->harga_tiket, 0, ',', '.') }} / tiket
                    </p>
                    <div class="flex justify-between items-center text-xs text-gray-500 mt-3">
                        <span>⭐ {{ number_format($wisata->rating ?? 0, 1) }}</span>
                        <span>
                            @if (isset($wisata->jarak) && !is_null($wisata->jarak))
                                {{ number_format($wisata->jarak, 1) }} km
                            @elseif ($filter === 'lokasi')
                                <span class="text-gray-400 italic">Aktifkan lokasi</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Detail Button -->
                <a href="{{ route('wisata.detail', $wisata->slug) }}" 
                   class="block text-center bg-teal-600 text-white py-2 text-sm font-medium hover:bg-teal-700 transition">
                    Lihat Detail
                </a>
            </div>
        </div>
        @endforeach
        
        <!-- Pagination -->
        <div class="w-full px-4">
            {{ $wisatas->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async function () {
    async function ambilLokasi() {
        if (!navigator.geolocation) {
            console.warn("Geolocation tidak didukung oleh browser ini.");
            return;
        }

        try {
            const position = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    timeout: 15000,
                    maximumAge: 60000
                });
            });

            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            Livewire.dispatch('userLocationUpdated', {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            });

        } catch (error) {
            console.error("Gagal mendapatkan lokasi:", error);
            const retryButton = document.createElement('button');
            retryButton.textContent = 'Coba Lagi Mengambil Lokasi';
            retryButton.className = 'px-4 py-2 bg-red-500 text-white rounded mt-4';
            retryButton.onclick = ambilLokasi;
            document.querySelector('.container').prepend(retryButton);
        }
    }

    await ambilLokasi();
});
</script>