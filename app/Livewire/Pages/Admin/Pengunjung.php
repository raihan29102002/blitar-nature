<?php

namespace App\Livewire\Pages\Admin;

use Livewire\Component;
use App\Models\Kunjungan;
use App\Models\Wisata;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Imports\KunjunganImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KunjunganExport;
use Livewire\WithFileUploads;


class Pengunjung extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads;
    protected $middleware = ['auth', 'role:admin'];
    public $wisata_id, $jumlah, $bulan, $tahun;
    public $kunjunganId;
    public $editMode = false;
    public $excelFile;
    public function importExcel()
    {
        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new KunjunganImport, $this->excelFile);

        session()->flash('message', 'Data berhasil diimpor!');
    }


    public function mount()
    {
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->wisata_id = '';
        $this->jumlah = '';
        $this->bulan = now()->month;
        $this->tahun = now()->year;
        $this->editMode = false;
        $this->kunjunganId = null;
    }
    public function create()
    {
        $this->resetFields();
        $this->editMode = true;
    }

    protected $paginationTheme = 'tailwind';
    public function save()
    {
        $this->validate([
            'wisata_id' => 'required|exists:wisatas,id',
            'jumlah' => 'required|numeric|min:0',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
        ]);

        if ($this->editMode) {
            Kunjungan::where('id', $this->kunjunganId)->update([
                'wisata_id' => $this->wisata_id,
                'jumlah' => $this->jumlah,
                'bulan' => $this->bulan,
                'tahun' => $this->tahun,
            ]);
        } else {
            Kunjungan::create([
                'wisata_id' => $this->wisata_id,
                'jumlah' => $this->jumlah,
                'bulan' => $this->bulan,
                'tahun' => $this->tahun,
            ]);
        }

        session()->flash('message', $this->editMode ? 'Data berhasil diperbarui!' : 'Data berhasil ditambahkan!');

        $this->resetFields();
    }

    public function edit($id)
    {
        $kunjungan = Kunjungan::findOrFail($id);
        $this->kunjunganId = $kunjungan->id;
        $this->wisata_id = $kunjungan->wisata_id;
        $this->jumlah = $kunjungan->jumlah;
        $this->bulan = $kunjungan->bulan;
        $this->tahun = $kunjungan->tahun;
        $this->editMode = true;
    }

    public function delete($id)
    {
        Kunjungan::findOrFail($id)->delete();
        session()->flash('message', 'Data berhasil dihapus!');
    }

    public function render()
    {
        return view('livewire.pages.admin.pengunjung', [
            'kunjunganList' => Kunjungan::orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->paginate(25),
            'wisataList' => Wisata::all(),
        ])->layout('layouts.admin');
    }
    public function exportExcel()
    {
        return Excel::download(new KunjunganExport, 'data_kunjungan.xlsx');
    }
}
