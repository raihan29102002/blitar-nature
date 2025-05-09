<div class="p-6 bg-white shadow-md rounded-lg w-full min-h-screen">
    <h1 class="text-3xl font-bold mt-6 mb-6 text-gray-700 ">Kelola Review Wisatawan</h1>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-800 p-2 mt-4 rounded">
            {{ session('message') }}
        </div>
    @endif
    <div class="mb-4">
        <input 
            type="text" 
            wire:model.live="search"
            placeholder="Cari berdasarkan nama pengguna atau wisata..." 
            class="w-full md:w-1/3 px-4 py-2 border rounded-lg shadow-sm"
        >
    </div>
    <table class="min-w-full table-auto text-sm text-left border-collapse mt-4">
        <thead>
            <tr class="bg-blue-100 text-gray-700 uppercase text-xs">
                <th class="px-4 py-3 border">No</th>
                <th class="px-4 py-3 border">Nama Wisata</th>
                <th class="px-4 py-3 border">Nama Pengguna</th>
                <th class="px-4 py-3 border">Rating</th>
                <th class="px-4 py-3 border">Komentar</th>
                <th class="px-4 py-3 border">Respon Admin</th>
                <th class="px-4 py-3 border text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reviews as $index => $review)
                <tr class="hover:bg-gray-100 transition">
                    <td class="px-4 py-3 border">{{ $index + 1 }}</td>
                    <td class="px-4 py-3 border">{{ $review->wisata->nama }}</td>
                    <td class="px-4 py-3 border">{{ $review->user->name }}</td>
                    <td class="px-4 py-3 border">{{ str_repeat('⭐', $review->rating) }}</td>
                    <td class="px-4 py-3 border">{{ $review->feedback }}</td>
                    <td class="px-4 py-3 border">{{ $review->response_admin ?? '-' }}</td>
                    <td class="px-4 py-3 border text-center">
                        @if (is_null($review->response_admin)) 
                            <button wire:click="respon({{ $review->id }})" class="text-blue-600 hover:underline">
                                📝 Respon
                            </button>
                        @else
                            <span class="text-gray-400">Sudah Direspon</span> 
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div x-data="{ open: @entangle('editMode') }" x-show="open" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Respon Review</h2>

            <textarea wire:model="responText" class="w-full border rounded-lg px-4 py-2 h-32" placeholder="Tulis respon admin di sini..."></textarea>

            <div class="flex justify-end mt-4">
                <button wire:click="simpanRespon" class="bg-green-600 text-white px-4 py-2 rounded-lg">Simpan</button>
                <button @click="open = false" class="ml-2 bg-gray-400 text-white px-4 py-2 rounded-lg">Batal</button>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $reviews->links('pagination::tailwind') }}
    </div>
</div>
