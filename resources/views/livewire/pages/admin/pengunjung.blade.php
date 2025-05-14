<div class="p-6 bg-white shadow-md rounded-lg w-full min-h-screen">
    <h1 class="text-3xl font-bold mt-6 mb-6 text-gray-700">Data Pengunjung Wisata</h1>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 flex-wrap">
        <button wire:click="create"
            class="inline-flex bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition self-start items-center"
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Pengunjung
        </button>

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
            <form wire:submit.prevent="importExcel" enctype="multipart/form-data" class="flex items-center gap-2">
                <input type="file" wire:model="excelFile"
                    class="text-sm text-gray-700 border border-gray-300 rounded-md p-1 bg-white">
                <button type="submit"
                    class="bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow hover:bg-green-700 transition inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 4v12" />
                    </svg>
                    Import Excel
                </button>
            </form>

            <button wire:click="exportExcel"
                class="bg-yellow-500 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow hover:bg-yellow-600 transition inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M16 10l-4-4m0 0l-4 4m4-4v12" />
                </svg>
                Export Wisata
            </button>
        </div>
    </div>


    @if (session()->has('message'))
    <div class="bg-green-100 text-green-800 p-2 mt-4 rounded">
        {{ session('message') }}
    </div>
    @endif

    <table class="min-w-full table-auto text-sm text-left border-collapse mt-4">
        <thead>
            <tr class="bg-blue-100 text-gray-700 uppercase text-xs">
                <th class="px-4 py-3 border">No</th>
                <th class="px-4 py-3 border">Wisata</th>
                <th class="px-4 py-3 border">Jumlah Pengunjung</th>
                <th class="px-4 py-3 border">Bulan</th>
                <th class="px-4 py-3 border">Tahun</th>
                <th class="px-4 py-3 border text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kunjunganList as $index => $kunjungan)
            <tr class="hover:bg-gray-100 transition">
                <td class="px-4 py-3 border">{{ $index + 1 }}</td>
                <td class="px-4 py-3 border font-medium text-gray-800">{{ $kunjungan->wisata->nama }}</td>
                <td class="px-4 py-3 border">{{ $kunjungan->jumlah }}</td>
                <td class="px-4 py-3 border">{{ $kunjungan->nama_bulan }}</td>
                <td class="px-4 py-3 border">{{ $kunjungan->tahun }}</td>
                <td class="px-4 py-3 border text-center">
                    <button wire:click="edit({{ $kunjungan->id }})"
                        class="inline-flex items-center text-yellow-500 font-semibold hover:underline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2l-6 6H9v-2l6-6zM4 20h16" />
                        </svg>
                        Edit
                    </button>
                    <button wire:click="delete({{ $kunjungan->id }})"
                        class="inline-flex items-center text-red-500 font-semibold hover:underline"
                        onclick="return confirm('Yakin ingin menghapus?')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Hapus
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Form -->
    <div x-data="{ open: @entangle('editMode') }" x-show="open"
        class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Tambah/Edit Data Pengunjung</h2>

            <label class="block mb-2">Wisata:</label>
            <select wire:model="wisata_id" class="w-full border rounded-lg px-4 py-2">
                <option value="">Pilih Wisata</option>
                @foreach($wisataList as $wisata)
                <option value="{{ $wisata->id }}">{{ $wisata->nama }}</option>
                @endforeach
            </select>

            <label class="block mt-4 mb-2">Jumlah Pengunjung:</label>
            <input type="number" wire:model="jumlah" class="w-full border rounded-lg px-4 py-2" min="0">

            <label class="block mt-4 mb-2">Bulan:</label>
            <select wire:model="bulan" class="w-full border rounded-lg px-4 py-2">
                @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}">{{ DateTime::createFromFormat('!m',
                    $i)->format('F') }}</option>
                    @endfor
            </select>

            <label class="block mt-4 mb-2">Tahun:</label>
            <input type="number" wire:model="tahun" class="w-full border rounded-lg px-4 py-2 mb-4" min="2000" max="2100">

            <div class="flex justify-end space-x-2">
                <button wire:click="save" class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1a2 2 0 014 0zm2-7v1a2 2 0 11-4 0v-1a2 2 0 014 0z" />
                    </svg>
                    Simpan
                </button>
                <button @click="open = false" class="inline-flex items-center bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Batal
                </button>
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $kunjunganList->links('pagination::tailwind') }}
    </div>
</div>