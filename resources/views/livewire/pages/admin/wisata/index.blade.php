<div class="p-6 bg-white shadow-md rounded-lg w-full min-h-screen flex flex-col flex-grow">
    <h1 class="text-2xl font-bold mb-6 text-gray-700">Data Wisata</h1>
    
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-2">
        <select wire:model.live="sortDirection" class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-1.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
            <option value="asc">Sort: A-Z</option>
            <option value="desc">Sort: Z-A</option>
        </select>
        <div class="relative w-full sm:w-auto">
            <input type="text" id="search" wire:model.live="search"
                class="w-full sm:w-64 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 pl-10 p-2.5"
                placeholder="Search wisata..." />
            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                🔍
            </div>
        </div>

    </div>

    <a href="{{ route('admin.wisata.create') }}"
        class="inline-flex bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition self-start">
        ➕ Tambah Wisata
    </a>

    <div class="overflow-x-auto mt-4 w-full">
        <table class="min-w-full table-auto text-sm text-left border-collapse">
            <thead>
                <tr class="bg-blue-100 text-gray-700 uppercase text-xs">
                    <th class="px-4 py-3 border">No</th>
                    <th class="px-4 py-3 border">Nama Wisata</th>
                    <th class="px-4 py-3 border">Koordinat X</th>
                    <th class="px-4 py-3 border">Koordinat Y</th>
                    <th class="px-4 py-3 border">Harga Tiket</th>
                    <th class="px-4 py-3 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($wisatas as $index => $wisata)
                    <tr class="hover:bg-gray-100 transition">
                        <td class="px-4 py-3 border">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 border font-medium text-gray-800">{{ $wisata->nama }}</td>
                        <td class="px-4 py-3 border">{{ $wisata->koordinat_x }}</td>
                        <td class="px-4 py-3 border">{{ $wisata->koordinat_y }}</td>
                        <td class="px-4 py-3 border font-semibold text-green-600">Rp{{ number_format($wisata->harga_tiket, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 border text-center">
                            <a href="{{ route('admin.wisata.edit', $wisata->id) }}"
                                class="text-yellow-500 font-semibold hover:underline">
                                ✏️ Edit
                            </a>
                            <a href="{{ route('admin.wisata.detail', $wisata->id) }}"
                                class="text-blue-500 font-semibold hover:underline">
                                🔍 Detail
                            </a>
                            <button wire:click="deleteWisata({{ $wisata->id }})"
                                class="ml-2 text-red-500 font-semibold hover:underline"
                                onclick="return confirm('Yakin ingin menghapus?')">
                                🗑️ Hapus
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $wisatas->links('pagination::tailwind') }}
    </div>
    
</div>
{{-- <livewire:pages.admin.wisata.detail/> --}}

