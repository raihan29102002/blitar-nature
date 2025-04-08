<div class="p-4">
    <h1 class="text-2xl font-bold mb-4">{{ $wisataId ? 'Edit Wisata' : 'Tambah Wisata' }}</h1>

    <div class="bg-white p-6 rounded shadow-md">
        <form wire:submit.prevent="save">
            <label class="block mb-2">Nama Wisata</label>
            <input type="text" wire:model="nama" class="w-full p-2 border rounded mb-4">

            <label class="block mb-2">Deskripsi</label>
            <textarea wire:model="deskripsi" class="w-full p-2 border rounded mb-4"></textarea>

            <label class="block mb-2">Koordinat X</label>
            <input type="text" wire:model="koordinat_x" class="w-full p-2 border rounded mb-4">

            <label class="block mb-2">Koordinat Y</label>
            <input type="text" wire:model="koordinat_y" class="w-full p-2 border rounded mb-4">

            
            <label class="block mb-2">Status Pengelolaan</label>
            <select wire:model="status_pengelolaan" class="w-full p-2 border rounded mb-4">
                <option value="">Pilih Status</option>
                <option value="dikelola">Dikelola</option>
                <option value="dibebaskan">Dibebaskan</option>
            </select>
            
            <label class="block mb-2">Status Tiket</label>
            <select wire:model="status_tiket" wire:change="checkTiketStatus" class="w-full p-2 border rounded mb-4">
                <option value="">Pilih Status</option>
                <option value="berbayar">Berbayar</option>
                <option value="gratis">Gratis</option>
            </select>

            @if($showHargaTiket)
                <label class="block mb-2">Harga Tiket</label>
                <input type="number" wire:model="harga_tiket" class="w-full p-2 border rounded mb-4">
            @endif


            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($allFasilitas as $fasilitas)
                    <label class="flex items-center space-x-2 bg-gray-100 p-2 rounded-md shadow-sm">
                        <input type="checkbox" wire:model="selectedFasilitas" value="{{ $fasilitas->id }}">
                        <span>{{ $fasilitas->nama_fasilitas }}</span>
                    </label>
                @endforeach
            </div>

            <label class="block mb-2">Upload Media</label>
            <input type="file" wire:model="mediaFiles" multiple class="w-full p-2 border rounded">
            @error('mediaFiles.*') <span class="text-red-500">{{ $message }}</span> @enderror
            <div wire:loading wire:target="mediaFiles">Uploading...</div>

            <div class="mt-4">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                <a href="{{ route('admin.wisata') }}" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Batal</a>
            </div>
        </form>
    </div>
</div>
