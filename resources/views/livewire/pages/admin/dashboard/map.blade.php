<div class="bg-white p-4 rounded-lg shadow-sm dark:bg-gray-800">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Peta Wisata</h2>
    <div id="map" style="width: 100%; height: 400px;"></div>
</div>

<script>
    async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");

        // Inisialisasi peta
        let map = new Map(document.getElementById('map'), {
            center: { lat: -8.0955, lng: 112.1686 }, 
            zoom: 10,
            mapId: "2356bde4d9f623d4",
            mapTypeId: "satellite"
        });

        let wisataList = @json($wisata);

        wisataList.forEach(function (wisata) {
            const pin = new PinElement({
                background: getPinColor(wisata.kategori),
                glyphColor: "white",
                borderColor: getPinColor(wisata.kategori)
            });

            const marker = new AdvancedMarkerElement({
                map: map,
                position: {
                    lat: parseFloat(wisata.koordinat_x),
                    lng: parseFloat(wisata.koordinat_y)
                },
                title: wisata.nama,
                content: pin.element
            });

            marker.addListener('click', () => {
                const info = new google.maps.InfoWindow({
                    content: `<h3>${wisata.nama}</h3><p>${wisata.deskripsi ?? ''}</p>`
                });
                info.open(map, marker);
            });

        });


        function getPinColor(kategori) {
            switch (kategori) {
                case 'pantai': return '#0096c7';      // biru laut
                case 'air_terjun': return '#00b4d8';  // tosca
                case 'pegunungan': return '#2a9d8f';  // hijau teal
                case 'perkemahan': return '#f4a261';  // oranye tanah
                case 'hutan': return '#588157';       // hijau gelap
                case 'perairan': return '#48cae4';    // biru muda
                default: return '#e63946';            // merah untuk lainnya
            }
        }
    }
</script>

<!-- Panggil API Google Maps dengan callback -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&v=beta&callback=initMap&loading=async">
</script>