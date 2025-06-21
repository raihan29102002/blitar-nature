<div class="bg-white p-4 rounded-lg shadow-sm dark:bg-gray-800">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Peta Wisata</h2>
    <div id="map" style="width: 100%; height: 400px;"></div>
</div>

<script>
    async function initMap() {
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");

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
                case 'Alam': return '#0096c7';      
                case 'Budaya': return '#f4a261';    
                case 'Buatan': return '#588157';   
                default: return '#e63946';         
            }
        }

        const legend = document.createElement('div');
        legend.id = 'legend';
        legend.style.backgroundColor = 'white';
        legend.style.padding = '10px';
        legend.style.borderRadius = '5px';
        legend.style.boxShadow = '0 2px 6px rgba(0,0,0,0.3)';
        legend.style.fontFamily = 'Arial, sans-serif';
        legend.style.fontSize = '14px';

        legend.innerHTML = `
            <h3 style="margin: 0 0 8px 0; font-size: 16px;">Kategori Wisata</h3>
            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                <div style="width: 16px; height: 16px; background: #0096c7; margin-right: 8px; border-radius: 50%;"></div>
                <span>Alam</span>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                <div style="width: 16px; height: 16px; background: #f4a261; margin-right: 8px; border-radius: 50%;"></div>
                <span>Budaya</span>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 5px;">
                <div style="width: 16px; height: 16px; background: #588157; margin-right: 8px; border-radius: 50%;"></div>
                <span>Buatan</span>
            </div>
            <div style="display: flex; align-items: center;">
                <div style="width: 16px; height: 16px; background: #e63946; margin-right: 8px; border-radius: 50%;"></div>
                <span>Lainnya</span>
            </div>
        `;

        map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(legend);
    }
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&v=beta&callback=initMap&loading=async">
</script>