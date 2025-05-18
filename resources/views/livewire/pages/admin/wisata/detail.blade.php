<div class="max-w-7xl mx-auto py-8 px-4 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg p-6 md:p-8 shadow md:col-span-2 space-y-6 self-start">
        <a href="{{ route('admin.wisata') }}"
            class="mt-4 inline-flex items-center bg-gray-500 text-white px-4 py-2 rounded">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <button wire:click="deleteWisata({{ $wisata->id }})"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 inline-flex items-center"
            onclick="return confirm('Yakin ingin menghapus?')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M10 3h4a1 1 0 011 1v1H9V4a1 1 0 011-1z" />
            </svg>
            Hapus
        </button>


        <h1 class="text-3xl font-bold text-green-700">{{ $wisata->nama }}</h1>
        <p class="text-gray-700">{{ $wisata->deskripsi }}</p>
        <div>
            @if($wisata->media && $wisata->media->isNotEmpty())
            <div class="swiper mySwiper rounded-xl overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach($wisata->media as $media)
                    @if(Str::endsWith($media->url, ['.jpg', '.png', '.jpeg', 'webp', '.gif']))
                    <div class="swiper-slide">
                        <img src="{{ $media->url }}" class="w-full h-128 object-cover rounded-xl" alt="Media Wisata">
                    </div>
                    @elseif(Str::endsWith($media->url, ['.mp4', '.webm']))
                    <div class="swiper-slide">
                        <video controls class="w-full h-64 object-cover rounded-xl">
                            <source src="{{ $media->url }}" type="video/mp4">
                            Browser tidak mendukung video.
                        </video>
                    </div>
                    @endif
                    @endforeach
                </div>
                <!-- Navigasi -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
            @else
            <p class="text-gray-500">Belum ada media untuk wisata ini.</p>
            @endif
        </div>
        <div class="text-sm text-gray-600">
            <p><strong>Harga Tiket:</strong> Rp{{ number_format($wisata->harga_tiket, 0, ',', '.') }}</p>
            <p><strong>Status Tiket:</strong> {{ $wisata->status_tiket }}</p>
            <p><strong>Status Pengelola:</strong> {{ $wisata->status_pengelolaan }}</p>
            <p><strong>Kategori:</strong> {{ $wisata->kategori }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 mt-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Fasilitas yang Tersedia</h2>
            @if($wisata->fasilitas && $wisata->fasilitas->isNotEmpty())
            <ul class="list-disc pl-5 text-gray-600 space-y-1">
                @foreach($wisata->fasilitas as $fasilitas)
                <li>{{ $fasilitas->nama_fasilitas }}</li>
                @endforeach
            </ul>
            @else
            <p class="text-gray-500 italic">Belum ada fasilitas yang tercatat untuk wisata ini.</p>
            @endif
        </div>

    </div>

    <div class="space-y-6">
        <!-- Map -->
        <div class="bg-white rounded-lg shadow p-4 mt-4 flex items-start space-x-4 text-sm text-gray-600">
            <div class="bg-white w-full h-64 rounded-lg mb-4 overflow-hidden shadow border" id="map"></div>
        </div>

        <div class="mt-4 space-y-2">
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $wisata->koordinat_x }},{{ $wisata->koordinat_y }}"
                target="_blank"
                class="inline-flex items-center justify-center w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                <!-- Heroicon: Map Pin -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 11c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 2C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7z" />
                </svg>
                Open Direction
            </a>

            <div id="alamat"
                class="bg-white rounded-lg shadow p-4 mt-4 flex items-start space-x-4 text-sm text-gray-600">
                <div class="flex-1">
                    <h6 class="font-semibold text-gray-800 mb-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-cyan-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 11c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 2C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7z" />
                        </svg>
                        Alamat
                    </h6>
                    <p id="alamat-text" class="italic mb-1">Mohon tunggu sebentarr</p>
                    <div class="mt-2">
                        <p class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 11c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 2C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7z" />
                            </svg>
                            <strong>Koordinat:</strong>
                            <span id="koordinat-text">{{ $wisata->koordinat_x }}, {{ $wisata->koordinat_y }}</span>
                        </p>
                        <div class="flex space-x-2 mt-1">
                            <button onclick="copyKoordinat()"
                                class="text-xs text-blue-600 hover:underline">Salin</button>
                            <a href="https://www.google.com/maps?q={{ $wisata->koordinat_x }},{{ $wisata->koordinat_y }}"
                                target="_blank" class="text-xs text-green-600 hover:underline">Buka di Google Maps</a>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Rata-rata Rating -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Rating Rata-rata</h2>
            <div class="text-3xl font-bold text-yellow-500">
                ★ {{ number_format($averageRating ?? 0, 1) }}/5
            </div>
        </div>
         @if($wisata->link_informasi)
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Informasi Lebih Lanjut</h2>
            <a href="{{ $wisata->link_informasi }}" target="_blank"
                class="inline-flex items-center justify-center bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M14.828 14.828a4 4 0 01-5.656 0L3 8m0 0l3-3m-3 3l3 3m6-3h6m0 0l-3-3m3 3l-3 3" />
                </svg>
                Kunjungi Halaman Informasi
            </a>
        </div>
        @endif
    </div>
    <script>
        let map;
        let marker;
    
        async function initMap() {
            try {
                const latLng = { lat: {{ $wisata->koordinat_x }}, lng: {{ $wisata->koordinat_y }} };
                
                const { Map } = await google.maps.importLibrary("maps");
                const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
                
                map = new Map(document.getElementById("map"), {
                    center: latLng,
                    zoom: 14,
                    mapId: 'DEMO_MAP_ID',
                });
                
                marker = new AdvancedMarkerElement({
                    map: map,
                    position: latLng,
                    title: '{{ $wisata->nama }}'
                });
    
                // Geocoding
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ location: latLng }, (results, status) => {
                    if (status === "OK" && results[0]) {
                        document.getElementById("alamat-text").textContent = results[0].formatted_address;
                    } else {
                        document.getElementById("alamat-text").textContent = "Alamat tidak ditemukan";
                    }
                });
            } catch (error) {
                console.error("Error loading Google Maps:", error);
                document.getElementById("map").innerHTML = '<p class="text-red-500">Gagal memuat peta. Silakan refresh halaman.</p>';
            }
        }
    
        // Pastikan initMap tersedia secara global
        window.initMap = initMap;
    
        // Handle Livewire events
        document.addEventListener("DOMContentLoaded", () => {
            // Load map when Livewire is ready
            if (typeof google !== 'undefined') {
                initMap();
            }
    
            // Refresh map when rating is submitted
            Livewire.on('rating-submitted', () => {
                if (typeof google !== 'undefined' && typeof initMap === 'function') {
                    initMap();
                }
            });
        });
    </script>

    <!-- Load Google Maps API -->
    <script>
        function loadGoogleMaps() {
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&v=weekly&loading=async&callback=initMap`;
            script.async = true;
            script.defer = true;
            script.onerror = () => {
                document.getElementById('map').innerHTML = '<p class="text-red-500">Gagal memuat Google Maps. Silakan coba lagi.</p>';
            };
            document.head.appendChild(script);
        }
    
        // Load Google Maps when Livewire is initialized
        document.addEventListener('livewire:init', () => {
            loadGoogleMaps();
        });
    </script>

    <script>
        function copyKoordinat() {
            const text = document.getElementById('koordinat-text').innerText;
            navigator.clipboard.writeText(text).then(() => {
                alert('Koordinat disalin ke clipboard');
            });
        }
    </script>
</div>