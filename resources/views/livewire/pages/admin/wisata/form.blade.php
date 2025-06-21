<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">{{ $wisataId ? 'Edit Wisata' : 'Tambah Wisata' }}</h1>

    <div class="bg-white p-6 rounded shadow-md">
        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <label class="block mb-2">Nama Wisata</label>
            <input type="text" wire:model="nama" placeholder="Contoh: Pantai Tambakrejo"
                class="w-full p-2 border rounded mb-4">

            <label class="block mb-2">Deskripsi</label>
            <textarea wire:model="deskripsi" placeholder="Deskripsi singkat wisata..."
                class="w-full p-2 border rounded mb-4"></textarea>

            <div class="mb-4">
                <label class="block mb-2">Pilih Lokasi di Peta</label>
                <div 
                    x-data="{
                        map: null,
                        marker: null,
                        initGoogleMaps() {
                            // Fungsi untuk memuat Google Maps
                            const loadMap = () => {
                                const defaultCenter = { 
                                    lat: {{ $koordinat_x ?: '-8.0955' }}, 
                                    lng: {{ $koordinat_y ?: '112.1686' }} 
                                };
                                
                                this.map = new google.maps.Map(this.$el, {
                                    center: defaultCenter,
                                    zoom: 14,
                                    mapTypeId: 'hybrid',
                                    gestureHandling: 'greedy'
                                });
                                
                                @if($koordinat_x && $koordinat_y)
                                    this.addMarker({ 
                                        lat: {{ $koordinat_x }}, 
                                        lng: {{ $koordinat_y }} 
                                    });
                                @endif
                                
                                this.map.addListener('click', (e) => {
                                    this.addMarker(e.latLng);
                                    @this.set('koordinat_x', e.latLng.lat().toFixed(6));
                                    @this.set('koordinat_y', e.latLng.lng().toFixed(6));
                                });
                            };
        
                            // Cek apakah Google Maps sudah dimuat
                            if (typeof google === 'undefined') {
                                const script = document.createElement('script');
                                script.src = `https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap`;
                                script.defer = true;
                                document.head.appendChild(script);
                                window.initMap = loadMap;
                            } else {
                                loadMap();
                            }
                        },
                        addMarker(position) {
                            if (this.marker) {
                                this.marker.setPosition(position);
                            } else {
                                this.marker = new google.maps.Marker({
                                    position: position,
                                    map: this.map,
                                    draggable: true,
                                    title: 'Lokasi Wisata'
                                });
                                
                                this.marker.addListener('dragend', (e) => {
                                    @this.set('koordinat_x', e.latLng.lat().toFixed(6));
                                    @this.set('koordinat_y', e.latLng.lng().toFixed(6));
                                });
                            }
                            this.map.panTo(position);
                        }
                    }"
                    x-init="initGoogleMaps()"
                    wire:ignore
                    id="googleMap" 
                    style="height: 350px; width: 100%; border-radius: 8px; border: 1px solid #e2e8f0;"
                ></div>
            </div>
        
            <!-- Input Koordinat (otomatis terisi) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-2">Latitude (X)</label>
                    <input 
                        type="text" 
                        wire:model="koordinat_x" 
                        placeholder="Contoh: -8.123456" 
                        class="w-full p-2 border rounded bg-gray-50"
                        readonly
                    >
                </div>
                <div>
                    <label class="block mb-2">Longitude (Y)</label>
                    <input 
                        type="text" 
                        wire:model="koordinat_y" 
                        placeholder="Contoh: 112.123456" 
                        class="w-full p-2 border rounded bg-gray-50"
                        readonly 
                    >
                </div>
            </div>

            <label class="block mb-2">Kategori</label>
            <select wire:model="kategori" class="w-full p-2 border rounded mb-4">
                <option value="">Pilih Kategori</option>
                <option value="Alam">Alam</option>
                <option value="Budaya">Budaya</option>
                <option value="Buatan">Buatan</option>
                <option value="Lainnya">Lainnya</option>
            </select>

            <label class="block mb-2">Status Pengelolaan</label>
            <select wire:model="status_pengelolaan" class="w-full p-2 border rounded mb-4">
                <option value="">Pilih Status</option>
                <option value="dikelola">Dikelola</option>
                <option value="dibebaskan">Dibebaskan</option>
            </select>

            <label class="block mb-2">Status Tiket</label>
            <select wire:model="status_tiket" wire:change="checkTiketStatus" class="w-full p-2 border rounded mb-4">
                <option value="">Pilih Status</option>
                <option value="berbayar">Berbayar</option>
                <option value="gratis">Gratis</option>
            </select>

            @if($showHargaTiket)
            <label class="block mb-2">Harga Tiket</label>
            <input type="number" wire:model="harga_tiket" placeholder="Contoh: 5000"
                class="w-full p-2 border rounded mb-4">
            @endif
            <div class="mb-4">
                <label for="link_informasi" class="block text-sm font-medium text-gray-700">Link Informasi
                    Tambahan</label>
                <input type="url" id="link_informasi" wire:model="link_informasi"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                <small class="text-gray-500">Contoh: https://instagram.com/namawisata atau
                    https://namawisata.com</small>
            </div>

            <label class="block mb-2">Fasilitas</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                @foreach($allFasilitas as $fasilitas)
                <label class="flex items-center space-x-2 bg-gray-100 p-2 rounded-md shadow-sm">
                    <input type="checkbox" wire:model="selectedFasilitas" value="{{ $fasilitas->id }}">
                    <span>{{ $fasilitas->nama_fasilitas }}</span>
                </label>
                @endforeach
            </div>

            <label class="block mb-2">Upload Media</label>
            <input type="file" wire:model="mediaFiles" multiple class="w-full p-2 border rounded">
            @error('mediaFiles.*') <span class="text-red-500">{{ $message }}</span> @enderror
            @if($uploadError)
            <div class="text-red-500 mt-2">{{ $uploadError }}</div>
            @endif
            <div wire:loading wire:target="mediaFiles" class="text-blue-500 mt-1">Uploading...</div>

            {{-- Menampilkan media lama jika sedang dalam mode edit --}}
            @if($wisataId && $mediaLama)
            <div class="mt-6">
                <h3 class="font-semibold mb-2">Media Sebelumnya:</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($mediaLama as $media)
                    @if($media['type'] === 'foto')
                    <img src="{{ $media['url'] }}" alt="Foto" class="w-full h-32 object-cover rounded">
                    @else
                    <video controls class="w-full h-32 rounded">
                        <source src="{{ $media['url'] }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    @endif
                    @endforeach
                </div>
            </div>
            @endif
            @if ($errors->any())
            <div class="text-red-500 mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="mt-6 flex gap-4">
                <button type="submit" wire:loading.attr="disabled" wire:target="mediaFiles"
                    class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1a2 2 0 014 0zm2-7v1a2 2 0 11-4 0v-1a2 2 0 014 0z" />
                    </svg>
                    Simpan
                </button>

                <a href="{{ route('admin.wisata') }}"
                    class="inline-flex items-center bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>