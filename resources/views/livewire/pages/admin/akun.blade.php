<div class="p-6 bg-white shadow-md rounded-lg w-full min-h-screen flex flex-col flex-grow">
    <h1 class="text-3xl font-bold mt-6 mb-6 text-gray-700">Data Akun Pengguna</h1>

    <button wire:click="openModal()"
        class="inline-flex bg-blue-600 text-white font-semibold px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition self-start">
        ➕ Tambah Akun
    </button>

    @if (session()->has('message'))
        <div class="mt-4 text-green-600 font-semibold">
            {{ session('message') }}
        </div>
    @endif

    <div class="overflow-x-auto mt-4 w-full">
        <table class="min-w-full table-auto text-sm text-left border-collapse">
            <thead>
                <tr class="bg-blue-100 text-gray-700 uppercase text-xs">
                    <th class="px-4 py-3 border">No</th>
                    <th class="px-4 py-3 border">Nama</th>
                    <th class="px-4 py-3 border">Email</th>
                    <th class="px-4 py-3 border">Role</th>
                    <th class="px-4 py-3 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($users as $index => $user)
                    <tr class="hover:bg-gray-100 transition">
                        <td class="px-4 py-3 border">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 border">{{ $user->name }}</td>
                        <td class="px-4 py-3 border">{{ $user->email }}</td>
                        <td class="px-4 py-3 border">{{ ucfirst($user->role) }}</td>
                        <td class="px-4 py-3 border text-center">
                            <button wire:click="openModal({{ $user->id }})"
                                class="text-yellow-500 font-semibold hover:underline">
                                ✏️ Edit
                            </button>
                            <button wire:click="deleteUser({{ $user->id }})"
                                class="ml-2 text-red-500 font-semibold hover:underline"
                                onclick="return confirm('Yakin ingin menghapus akun ini?')">
                                🗑️ Hapus
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if ($modal)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">{{ $userId ? 'Edit Akun' : 'Tambah Akun' }}</h2>

                <div class="mb-4">
                    <label for="name" class="block text-gray-600 font-medium mb-2">Nama</label>
                    <input type="text" wire:model="name" id="name"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-600 font-medium mb-2">Email</label>
                    <input type="email" wire:model="email" id="email"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="role" class="block text-gray-600 font-medium mb-2">Role</label>
                    <select wire:model="role" id="role"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="wisatawan">Wisatawan</option>
                    </select>
                    @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-600 font-medium mb-2">Password</label>
                    <input type="password" wire:model="password" id="password"
                        class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <button wire:click="save"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
                        💾 Simpan
                    </button>
                    <button wire:click="closeModal"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-600 transition">
                        ❌ Batal
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
