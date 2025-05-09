<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-10 text-center">Daftar Wisata Alam</h1>
    <div class="flex justify-center mb-6">
        <input type="text" wire:model.live="search"
            class="w-full md:w-1/2 px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:border-teal-400"
            placeholder="Cari nama wisata...">
    </div>

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

    <div class="flex flex-wrap -mx-4 mb-24">
        @foreach ($wisatas as $wisata)
        <div class="w-full md:w-1/2 lg:w-1/4 px-4 mb-8">
            <div class="bg-white rounded-2xl shadow-md overflow-hidden flex flex-col h-full wisata-item"
                data-id="{{ $wisata->id }}"
                data-lat="{{ $wisata->koordinat_x }}"
                data-lng="{{ $wisata->koordinat_y }}">
                @if ($wisata->resized_image_url)
                <img src="{{ $wisata->resized_image_url }}" alt="{{ $wisata->nama }}"
                    class="w-full h-48 object-cover rounded-xl mb-4">
                @else
                <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded-xl mb-4 text-gray-600">
                    Tidak ada gambar
                </div>
                @endif

                <div class="p-5 flex-1 flex flex-col justify-between">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $wisata->nama }}</h2>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ \Illuminate\Support\Str::limit($wisata->deskripsi, 100) }}
                    </p>
                    <p class="text-sm text-gray-700 mt-auto">
                        {{ $wisata->harga_tiket == 0 ? 'Gratis' : 'Rp' . number_format($wisata->harga_tiket, 0, ',',
                        '.') }} / tiket
                    </p>
                    <div class="flex justify-between items-center text-xs text-gray-500 mt-3">
                        <span>⭐ {{ number_format($wisata->rating ?? 0, 1) }}</span>
                        <span class="jarak-display">
                            @if (isset($wisata->jarak) && !is_null($wisata->jarak))
                                {{ number_format($wisata->jarak, 1) }} km
                            @elseif ($filter === 'lokasi')
                                <span class="text-gray-400 italic animate-pulse">Menghitung jarak...</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </span>
                    </div>
                </div>

                <a href='{{ route('wisata.detail', $wisata->id) }}' class="block text-center bg-teal-600 text-white
                    py-2 text-sm font-medium hover:bg-teal-700 transition">
                    Lihat Detail
                </a>
            </div>
        </div>
        @endforeach
        <div class="w-full px-4">
            {{ $wisatas->links() }}
        </div>
    </div>
</div>

<script async src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places">
</script>
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

                console.log('Latitude:', userLat, 'Longitude:', userLng);  // Debugging log
                console.log(`Mengirim koordinat ke Livewire: Lat = ${userLat}, Lng = ${userLng}`);

                Livewire.dispatch('userLocationUpdated', { lat: userLat, lng: userLng });

                hitungJarakSemua(userLat, userLng);

            } catch (error) {
                console.error("Gagal mendapatkan lokasi:", error);
                // Menampilkan tombol retry jika gagal
                const retryButton = document.createElement('button');
                retryButton.textContent = 'Coba Lagi Mengambil Lokasi';
                retryButton.className = 'px-4 py-2 bg-red-500 text-white rounded mt-4';
                retryButton.onclick = ambilLokasi;
                document.querySelector('.container').prepend(retryButton);
            }
        }

        function hitungJarakSemua(userLat, userLng) {
            const service = new google.maps.DistanceMatrixService();
            const origins = [new google.maps.LatLng(userLat, userLng)];
            const destinations = [];
            const wisataElements = [];
            
            // Kumpulkan elemen wisata yang valid
            document.querySelectorAll('.wisata-item').forEach(item => {
                const id = item.getAttribute('data-id');
                const lat = parseFloat(item.getAttribute('data-lat'));
                const lng = parseFloat(item.getAttribute('data-lng'));
                
                if (id && !isNaN(lat) && !isNaN(lng)) {
                    wisataElements.push({
                        element: item,
                        id: id,
                        latLng: new google.maps.LatLng(lat, lng)
                    });
                } else {
                    console.warn(`Wisata dengan ID ${id} memiliki koordinat tidak valid`, {lat, lng});
                    const distanceEl = item.querySelector('.jarak-display');
                    if (distanceEl) {
                        distanceEl.textContent = 'Lokasi tidak valid';
                    }
                }
            });

            if (wisataElements.length === 0) {
                console.warn("Tidak ada destinasi wisata yang valid");
                return;
            }

            // Siapkan destinasi untuk API
            const validDestinations = wisataElements.map(w => w.latLng);
            
            service.getDistanceMatrix({
                origins: origins,
                destinations: validDestinations,
                travelMode: google.maps.TravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC
            }, (response, status) => {
                if (status !== "OK") {
                    console.error("Error:", status, response);
                    wisataElements.forEach(w => {
                        const distanceEl = w.element.querySelector('.jarak-display');
                        if (distanceEl) distanceEl.textContent = 'Error menghitung jarak';
                    });
                    return;
                }

                response.rows[0].elements.forEach((result, index) => {
                    const wisata = wisataElements[index];
                    const distanceEl = wisata.element.querySelector('.jarak-display');
                    
                    if (distanceEl) {
                        if (result.status === "OK") {
                            distanceEl.textContent = `${(result.distance.value / 1000).toFixed(1)} km`;
                        } else {
                            distanceEl.textContent = 'Jarak tidak tersedia';
                        }
                    }
                });
            });
            // Livewire.on('dom-updated', () => {
            //     navigator.geolocation.getCurrentPosition(
            //         (position) => {
            //             const userLat = position.coords.latitude;
            //             const userLng = position.coords.longitude;
            //             hitungJarakSemua(userLat, userLng);
            //         },
            //         (error) => {
            //             console.error("Gagal mendapatkan lokasi saat update DOM:", error);
            //         },
            //         { timeout: 15000, maximumAge: 60000 }
            //     );
            // });
        }
        



        await ambilLokasi();
    });
</script>