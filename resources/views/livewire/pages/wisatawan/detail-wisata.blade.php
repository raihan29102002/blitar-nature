<div class="max-w-7xl mx-auto py-8 px-4 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg p-6 md:p-8 shadow md:col-span-2 space-y-6 self-start">
        <h1 class="text-3xl font-bold text-green-700">{{ $wisata->nama }}</h1>
        <p class="text-gray-700">{{ $wisata->deskripsi }}</p>
        <div>
            @if($wisata->media && $wisata->media->isNotEmpty())
            @foreach($wisata->media as $media)
            @if(Str::endsWith($media->url, ['.jpg', '.png', '.jpeg', 'webp', '.gif']))
            <img src="{{ $media->url }}" class="rounded-xl mb-4 w-full" alt="Media Wisata">
            @elseif(Str::endsWith($media->url, ['.mp4', '.webm']))
            <video controls class="rounded-xl mb-4 w-full">
                <source src="{{ $media->url }}" type="video/mp4">
                Browser tidak mendukung video.
            </video>
            @endif
            @endforeach
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


    <!-- Sidebar: Maps dan Form Feedback -->
    <div class="space-y-6">
        <!-- Map -->
        <div class="bg-white rounded-lg shadow p-4 mt-4 flex items-start space-x-4 text-sm text-gray-600">
            <div class="bg-white w-full h-64 rounded-lg mb-4 overflow-hidden shadow border" id="map"></div>
        </div>

        <div class="mt-4 space-y-2">
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $wisata->koordinat_x }},{{ $wisata->koordinat_y }}"
                target="_blank"
                class="block text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                📍 Open Direction
            </a>
            <div id="alamat"
                class="bg-white rounded-lg shadow p-4 mt-4 flex items-start space-x-4 text-sm text-gray-600">
                <!-- Ikon lokasi -->
                <div class="flex-shrink-0">
                    <div class="p-2 rounded-full bg-cyan-500 text-white">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                </div>

                <!-- Info alamat dan koordinat -->
                <div class="flex-1">
                    <h6 class="font-semibold text-gray-800 mb-1">Alamat</h6>
                    <p id="alamat-text" class="italic mb-1">Mohon tunggu sebentarr</p>

                    <div class="mt-2">
                        <p><strong>Koordinat:</strong>
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

        <!-- Form Rating -->
        @auth
        @if(auth()->user()->role !== 'admin')
        <!-- Form Rating -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Beri Feedback</h2>
            <form wire:submit.prevent="submitRating" class="space-y-4">
                <div x-data="{ 
                    rating: {{ $rating ?? 0 }}, 
                    hoverRating: 0,
                    setRating(value) {
                        this.rating = value;
                        @this.set('rating', value);
                    }
                }" class="space-y-1">
                    <label class="block text-sm font-medium text-gray-600">Rating</label>
                    <div class="flex space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg 
                                @click="setRating({{ $i }})" 
                                @mouseover="hoverRating = {{ $i }}" 
                                @mouseleave="hoverRating = 0"
                                :class="{
                                    'text-yellow-400': {{ $i }} <= (hoverRating || rating),
                                    'text-gray-300': {{ $i }} > (hoverRating || rating)
                                }" 
                                class="w-8 h-8 cursor-pointer transition-colors" 
                                fill="currentColor" 
                                viewBox="0 0 20 20"
                            >
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.163c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.286 3.955c.3.921-.755 1.688-1.54 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.286-3.955a1 1 0 00-.364-1.118L2.07 9.382c-.783-.57-.38-1.81.588-1.81h4.163a1 1 0 00.95-.69l1.286-3.955z"/>
                            </svg>
                        @endfor
                    </div>
                    @error('rating') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
        
                <div>
                    <label for="feedback" class="block text-sm font-medium text-gray-600">Komentar</label>
                    <textarea wire:model.defer="feedback" id="feedback" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                    @error('feedback') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">
                    Kirim
                </button>
            </form>
        </div>
        @endif
        @else
        <!-- Jika belum login -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Beri Feedback</h2>
            @if (session()->has('success'))
            <div class="p-2 mb-3 text-green-700 bg-green-100 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if (session()->has('error'))
            <div class="p-2 mb-3 text-red-700 bg-red-100 rounded">
                {{ session('error') }}
            </div>
            @endif
            <form onsubmit="return showLoginAlert(event)" class="space-y-4">
                <!-- form disabled -->
                <div>
                    <label class="block text-sm font-medium text-gray-600">Rating</label>
                    <div class="flex space-x-1 opacity-50 pointer-events-none">
                        @for ($i = 1; $i <= 5; $i++) <svg class="w-8 h-8 text-gray-300" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l..." />
                            </svg>
                            @endfor
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600">Komentar</label>
                    <textarea disabled class="w-full border rounded px-3 py-2 opacity-50" rows="3"></textarea>
                </div>
                <button type="submit"
                    class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">Kirim</button>
            </form>
        </div>
        @endauth

    </div>

    @if($wisata->ratings && $wisata->ratings->isNotEmpty())
    <div class="bg-white rounded-lg shadow p-4 space-y-4">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Ulasan Pengunjung</h2>
        @foreach($wisata->ratings->sortByDesc('created_at') as $fb)
        <div class="border-t pt-2">
            <div class="text-sm text-gray-700 font-semibold mb-1">
                {{ $fb->user->name ?? 'Anonim' }}
            </div>
            <div class="text-yellow-500 text-sm mb-1">
                @for($i = 1; $i <= 5; $i++) @if($i <=$fb->rating) ★ @else ☆ @endif
                    @endfor
                    <span class="text-gray-500 ml-2 text-xs">{{ $fb->created_at->format('d M Y') }}</span>
            </div>
            <div class="text-sm text-gray-700 mb-1">
                <strong>Komentar:</strong> {{ $fb->feedback ?? '-' }}
            </div>
            @if($fb->response_admin)
            <div class="bg-gray-100 text-sm text-gray-600 p-2 rounded">
                <strong>Respon Admin:</strong> {{ $fb->response_admin }}
            </div>
            @endif

            @auth
            @if(auth()->user()->role === 'admin')
            <div class="mt-2">
                <form wire:submit.prevent="submitAdminResponse({{ $fb->id }})" class="space-y-2">
                    <textarea wire:model.defer="response_admin.{{ $fb->id }}" rows="2"
                        class="w-full border rounded px-3 py-2 text-sm" placeholder="Tulis balasan admin..."></textarea>
                    <button type="submit"
                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-sm">Balas</button>
                </form>
            </div>
            @endif
            @endauth

        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">Ulasan Pengunjung</h2>
        <p class="text-sm text-gray-500">Belum ada ulasan.</p>
    </div>
    @endif

    <!-- Script Map -->
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
        function showLoginAlert(event) {
            event.preventDefault();
            if (confirm("Anda harus login terlebih dahulu untuk memberi feedback.\nKlik OK untuk login atau Cancel untuk kembali.")) {
                window.location.href = "{{ route('login') }}";
            }
            return false;
        }
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