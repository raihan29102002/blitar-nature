<?php

namespace App\Livewire\Pages\Admin\Dashboard;

use Livewire\Component;
use App\Models\Kunjungan;
use Carbon\Carbon;

class KunjunganChart extends Component
{
    public $labels = [];
    public $datasets = [];
    public $tahun;
    public $tahunList = [];

    public function generateChartData()
    {
        $bulanNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $this->labels = array_values($bulanNames);
        $this->datasets = [];

        $query = Kunjungan::selectRaw('CAST(bulan AS UNSIGNED) as bulan, tahun, SUM(jumlah) as total')
            ->groupBy('bulan', 'tahun')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan');

        if (!empty($this->tahun)) {
            $query->where('tahun', $this->tahun);
        }

        $data = $query->get();

        $tahunData = $data->groupBy('tahun');

        foreach ($tahunData as $tahun => $dataTahun) {
            $dataset = [
                'label' => "Tahun $tahun",
                'data' => array_fill(0, 12, 0),
                'borderColor' => $this->generateColor(),
                'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                'borderWidth' => 2,
                'tension' => 0.4
            ];

            foreach ($dataTahun as $item) {
                $dataset['data'][$item->bulan - 1] = $item->total;
            }

            $this->datasets[] = $dataset;
        }
    }

    private function generateColor()
    {
        return 'rgba(' . rand(50, 200) . ',' . rand(50, 200) . ',' . rand(50, 200) . ',1)';
    }

    public function mount()
    {
        $this->tahunList = Kunjungan::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun')->toArray();
        $this->tahun = null;
        $this->generateChartData();
    }

    public function updatedTahun()
    {
        $this->generateChartData();
        $this->dispatch('updateChart', labels: $this->labels, datasets: $this->datasets);
    }

    public function render()
    {
        return view('livewire.pages.admin.dashboard.kunjungan-chart');
    }
}