<div class="max-w-full bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 md:p-6">
    <div class="flex justify-between mb-5">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Grafik Kunjungan Wisata</h2>
        </div>
        <div>
            <label for="tahun" class="font-medium text-gray-700 dark:text-gray-300">Pilih Tahun:</label>
            <select wire:model.live="tahun" id="tahun" class="border rounded p-1 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="">Semua Tahun</option>
                @foreach ($tahunList as $thn)
                    <option value="{{ $thn }}">{{ $thn }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div id="line-chart" class="h-64">
        <canvas id="kunjunganChart"></canvas>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let ctx = document.getElementById('kunjunganChart').getContext('2d');
            let chartInstance = null;
    
            function createChart(labels, datasets) {
                if (chartInstance) {
                    chartInstance.destroy();
                }
    
                chartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(243, 244, 246, 0.3)'
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(243, 244, 246, 0.3)'
                                }
                            }
                        }
                    }
                });
            }
    
            createChart(@json($labels), @json($datasets));
    
            Livewire.on('updateChart', ({ labels, datasets }) => {  
                setTimeout(() => {
                    createChart(labels, datasets);
                }, 100);
            });
        });
    </script>
</div>
