<div class="p-6 bg-white shadow-md rounded-lg w-full min-h-screen flex flex-col flex-grow">
    <h1 class="text-3xl font-bold mt-6 mb-6 text-gray-700">Data Fasilitas</h1>
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div class="relative w-full sm:w-64">
            <input type="text" id="search" wire:model.live="search"
                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg pl-10 pr-3 py-2.5 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Search Fasilitas..." />
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1110 2.5a7.5 7.5 0 016.65 14.15z" />
                </svg>
            </div>
        </div>
    </div>

    <button wire:click="openModal()"
        class="inline-flex bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition self-start items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Fasilitas
    </button>

    <div class="overflow-x-auto mt-4 w-full">
        <table class="min-w-full table-auto text-sm text-left border-collapse">
            <thead>
                <tr class="bg-blue-100 text-gray-700 uppercase text-xs">
                    <th class="px-4 py-3 border">No</th>
                    <th class="px-4 py-3 border">Nama Fasilitas</th>
                    <th class="px-4 py-3 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($fasilitasList as $index => $item)
                <tr class="hover:bg-gray-100 transition">
                    <td class="px-4 py-3 border">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 border font-medium text-gray-800">{{ $item['nama_fasilitas'] }}</td>
                    <td class="px-4 py-3 border text-center">
                        <button wire:click="openModal({{ $item['id'] }})"
                            class="inline-flex items-center text-yellow-500 font-semibold hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536M9 11l6-6m2 2l-6 6H9v-2l6-6zM4 20h16" />
                            </svg>
                            Edit
                        </button>
                        <button wire:click="deleteFasilitas({{ $item['id'] }})"
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
    </div>

    @if ($modal)
    <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">{{ $id ? 'Edit Fasilitas' : 'Tambah Fasilitas' }}</h2>

            <div class="mb-4">
                <label for="nama" class="block text-gray-600 font-medium mb-2">Nama Fasilitas</label>
                <input type="text" wire:model="nama_fasilitas" id="nama_fasilitas"
                    class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('nama_fasilitas') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <button wire:click="save"
                    class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 11-4 0v-1a2 2 0 014 0zm2-7v1a2 2 0 11-4 0v-1a2 2 0 014 0z" />
                    </svg>
                    Simpan
                </button>
                <button wire:click="closeModal"
                    class="inline-flex items-center bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600 transition">
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
    @endif
</div>