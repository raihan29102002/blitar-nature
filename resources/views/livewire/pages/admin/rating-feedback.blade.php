<div class="p-6 bg-white shadow-md rounded-lg w-full min-h-screen">
    <h1 class="text-3xl font-bold mt-6 mb-6 text-gray-700 ">Data Review Wisatawan</h1>

    <div class="mb-4 relative w-full md:w-1/3">
        <input type="text" wire:model.live="search" placeholder="Cari berdasarkan nama pengguna atau wisata..."
            class="w-full pl-10 pr-4 py-2 border rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1116.65 6.65a7.5 7.5 0 010 10.6z" />
            </svg>
        </div>
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
                    <button wire:click="respon({{ $review->id }})"
                        class="inline-flex items-center text-blue-600 font-semibold hover:underline">
                        <!-- Heroicon: Chat Bubble Left Ellipsis -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M21 12c0-4.418-4.03-8-9-8S3 7.582 3 12c0 1.386.438 2.684 1.196 3.75A8.962 8.962 0 003 21l3.75-1.196A8.962 8.962 0 0012 21c4.97 0 9-3.582 9-8z" />
                        </svg>
                        Respon
                    </button>
                    @else
                    <span class="text-gray-400">Sudah Direspon</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div x-data="{ open: @entangle('editMode') }" x-show="open"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Respon Review</h2>

            <textarea wire:model="responText" class="w-full border rounded-lg px-4 py-2 h-32"
                placeholder="Tulis respon admin di sini..."></textarea>

            <div class="flex justify-end mt-4">
                <button wire:click="simpanRespon" class="bg-green-600 text-white px-4 py-2 rounded-lg">Simpan</button>
                <button @click="open = false" class="ml-2 bg-gray-400 text-white px-4 py-2 rounded-lg">Batal</button>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $reviews->links() }}
    </div>
</div>