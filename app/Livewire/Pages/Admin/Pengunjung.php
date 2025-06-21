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
    public $wisata_id, $jumlah, $bulan, $tahun;
    public $kunjunganId;
    public $editMode = false;
    public $showModal = false;
    public $search = '';
    public $excelFile;
    public function importExcel()
    {
        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new KunjunganImport, $this->excelFile);

        $this->dispatch('showToast', 'success', 'Data Pengunjung berhasil diimport.');
    }

    public function updatedSearch()
    {
        $this->resetPage();
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
        $this->editMode = false;
        $this->showModal = true;
    }

    public function save()
    {
        try {
            $this->validate([
                'wisata_id' => 'required|numeric',
                'jumlah' => 'required|numeric|min:0',
                'bulan' => 'required|numeric|min:1|max:12',
                'tahun' => 'required|numeric|min:2000|max:2100',
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
    
            $this->dispatch('showToast', 'success', $this->editMode
                ? 'Data Pengunjung berhasil diupdate.'
                : 'Data Pengunjung berhasil ditambahkan.');
    
            $this->resetFields();
            \Log::info('Creating new kunjungan', [
                'wisata_id' => $this->wisata_id,
                'jumlah' => $this->jumlah,
                'bulan' => $this->bulan,
                'tahun' => $this->tahun,
            ]);
        } catch (\Exception $e) {
            \Log::info('Creating new kunjungan', [
                'wisata_id' => $this->wisata_id,
                'jumlah' => $this->jumlah,
                'bulan' => $this->bulan,
                'tahun' => $this->tahun,
            ]);
        }
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
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function delete($id)
    {
        Kunjungan::findOrFail($id)->delete();
        $this->dispatch('showToast', 'success', 'Data Pengunjung berhasil dihapus.');
    }

    public function render()
    {
        $kunjunganQuery = Kunjungan::with('wisata')
            ->when($this->search, function ($query) {
                $query->whereHas('wisata', function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc');
    
        return view('livewire.pages.admin.pengunjung', [
            'kunjunganList' => $kunjunganQuery->paginate(25),
            'wisataList' => Wisata::all(),
        ])->layout('layouts.admin');
    }
    public function exportExcel()
    {
        return Excel::download(new KunjunganExport, 'data_kunjungan.xlsx');
    }
}
