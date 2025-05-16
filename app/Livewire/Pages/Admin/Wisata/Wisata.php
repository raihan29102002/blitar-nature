<?php

namespace App\Livewire\Pages\Admin\Wisata;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\Wisata as WisataModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\WisataImport;
use App\Exports\WisataExport;
use Livewire\WithFileUploads;

class Wisata extends Component
{
    use WithPagination, WithoutUrlPagination;
    use WithFileUploads;
    public $excelFile;

    public $search = '';
    public $sortDirection = 'asc';
    
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSortDirection()
    {
        $this->resetPage();
    }


    protected $paginationTheme = 'tailwind';
    
    protected $middleware = ['auth', 'role:admin'];
    public $nama, $deskripsi, $koordinat_x, $koordinat_y, $status_pengelolaan, $harga_tiket, $status_tiket, $wisataId;
    public $isModalOpen = false;
    protected $rules = [
        'nama' => 'required',
        'deskripsi' => 'required',
        'koordinat_x' => 'required',
        'koordinat_y' => 'required',
        'status_pengelolaan' => 'required',
        'harga_tiket' => 'required|numeric',
        'status_tiket' => 'required',
    ];
    protected $layout = 'layouts.admin';
    public function render()
    {
        $wisatas = WisataModel::where('nama', 'like', '%' . $this->search . '%')
            ->orderBy('nama', $this->sortDirection)
            ->paginate(10);

        return view('livewire.pages.admin.wisata.index', compact('wisatas'))
            ->layout('layouts.admin');
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->nama = '';
        $this->deskripsi = '';
        $this->koordinat_x = '';
        $this->koordinat_y = '';
        $this->status_pengelolaan = '';
        $this->harga_tiket = '';
        $this->status_tiket = '';
        $this->wisataId = null;
    }

    public function saveWisata()
    {
        $this->validate();

        WisataModel::updateOrCreate(['id' => $this->wisataId], [
            'nama' => $this->nama,
            'deskripsi' => $this->deskripsi,
            'koordinat_x' => $this->koordinat_x,
            'koordinat_y' => $this->koordinat_y,
            'status_pengelolaan' => $this->status_pengelolaan,
            'harga_tiket' => $this->harga_tiket,
            'status_tiket' => $this->status_tiket,
        ]);

        session()->flash('message', $this->wisataId ? 'Wisata berhasil diupdate.' : 'Wisata berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function editWisata($id)
    {
        $wisata = WisataModel::findOrFail($id);
        $this->wisataId = $wisata->id;
        $this->nama = $wisata->nama;
        $this->koordinat_x = $wisata->koordinat_x;
        $this->koordinat_y = $wisata->koordinat_y;
        $this->harga_tiket = $wisata->harga_tiket;
        $this->deskripsi = $wisata->deskripsi;
        $this->status_pengelolaan = $wisata->status_pengelolaan;
        $this->status_tiket = $wisata->status_tiket;

        $this->isModalOpen = true;
    }


    public function deleteWisata($id)
    {
        WisataModel::find($id)->delete();
        session()->flash('message', 'Wisata berhasil dihapus.');
    }
    public function importExcel()
    {
        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new WisataImport, $this->excelFile);

        session()->flash('message', 'Data wisata berhasil diimpor!');
    }

    public function exportWisata()
    {
        return Excel::download(new WisataExport, 'data_wisata.xlsx');
    }

}