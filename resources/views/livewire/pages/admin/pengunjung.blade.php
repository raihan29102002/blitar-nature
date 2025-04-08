<div class="p-6 bg-white shadow-md rounded-lg w-full min-h-screen">
    <h1 class="text-2xl font-bold mb-6 text-gray-700">Data Pengunjung Wisata</h1>

    <button wire:click="resetFields" class="bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition" x-data="{ open: false }" @click="open = true">
        ➕ Tambah Data Pengunjung
    </button>

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
                        <button wire:click="edit({{ $kunjungan->id }})" class="text-yellow-500 font-semibold hover:underline">
                            ✏️ Edit
                        </button>
                        <button wire:click="delete({{ $kunjungan->id }})" class="ml-2 text-red-500 font-semibold hover:underline" onclick="return confirm('Yakin ingin menghapus?')">
                            🗑️ Hapus
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Form -->
    <div x-data="{ open: @entangle('editMode') }" x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
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
                @for ($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}">{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                @endfor
            </select>

            <label class="block mt-4 mb-2">Tahun:</label>
            <input type="number" wire:model="tahun" class="w-full border rounded-lg px-4 py-2" min="2000" max="2100">

            <div class="flex justify-end mt-4">
                <button wire:click="save" class="bg-green-600 text-white px-4 py-2 rounded-lg">Simpan</button>
                <button @click="open = false" class="ml-2 bg-gray-400 text-white px-4 py-2 rounded-lg">Batal</button>
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $kunjunganList->links('pagination::tailwind') }}
    </div>
</div>
