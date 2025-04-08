<div class="bg-white p-4 rounded-lg shadow-sm dark:bg-gray-800">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Peta Wisata</h2>
    <div id="map" style="width: 100%; height: 400px;"></div>
</div>

<script>
    async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

        // Inisialisasi peta
        let map = new Map(document.getElementById('map'), {
            center: { lat: -8.0955, lng: 112.1686 }, 
            zoom: 10,
            mapId: "MAP_WISATA_BLITAR",
            mapTypeId: "satellite"
        });

        let wisataList = @json($wisata);

        wisataList.forEach(function (wisata) {
            new AdvancedMarkerElement({
                map: map,
                position: { lat: parseFloat(wisata.koordinat_x), lng: parseFloat(wisata.koordinat_y) },
                title: wisata.nama
            });
        });
    }
</script>

<!-- Panggil API Google Maps dengan callback -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&v=beta&callback=initMap&loading=async"></script>
