<div class="min-h-screen bg-gray-100 p-10">
    <h1 class="text-3xl font-bold  mb-6 text-gray-700">Dashboard Admin</h1>
    <button onclick="exportDashboardPdf()"
        class="inline-flex items-center gap-2 px-4 py-2 mb-4 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 text-sm font-semibold transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-3 3v-6m4-3H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V8l-6-6z" />
        </svg>
        Export PDF
    </button>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
        <livewire:pages.admin.dashboard.kunjungan-chart />
        <livewire:pages.admin.dashboard.statistics />
    </div>
    <div class="mt-6">
        <livewire:pages.admin.dashboard.map />
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    function exportDashboardPdf() {
        const chartCanvas = document.getElementById('kunjunganChart');
        const chartImage = chartCanvas.toDataURL('image/png');

        html2canvas(document.getElementById('map')).then(function(mapCanvas) {
            const mapImage = mapCanvas.toDataURL('image/png');

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.export.pdf") }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';

            const inputChart = document.createElement('input');
            inputChart.type = 'hidden';
            inputChart.name = 'chart_image';
            inputChart.value = chartImage;

            const inputMap = document.createElement('input');
            inputMap.type = 'hidden';
            inputMap.name = 'map_image';
            inputMap.value = mapImage;

            form.appendChild(csrf);
            form.appendChild(inputChart);
            form.appendChild(inputMap);

            document.body.appendChild(form);
            form.submit();
        });
    }
</script>