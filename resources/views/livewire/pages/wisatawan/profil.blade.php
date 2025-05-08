<div class="max-w-4xl mx-auto p-6 mt-8 bg-white rounded-2xl shadow-lg space-y-8">
    <h2 class="text-2xl font-semibold text-gray-800 text-center">Profil Wisatawan</h2>

    <!-- Informasi Akun -->
    <div class="space-y-2">
        <h3 class="text-lg font-semibold text-gray-700">Informasi Akun</h3>
        <div class="text-gray-600">
            <p><span class="font-medium">Nama:</span> {{ $user->name }}</p>
            <p><span class="font-medium">Email:</span> {{ $user->email }}</p>
            <p><span class="font-medium">Tanggal Registrasi:</span> {{ $user->created_at->format('d M Y') }}</p>
        </div>
    </div>

    <!-- Aktivitas Wisata -->
    <div class="space-y-2">
        <h3 class="text-lg font-semibold text-gray-700">Aktivitas Wisata</h3>
        @if($user->ratings->count())
            <ul class="space-y-2">
                @foreach($user->ratings as $rating)
                    <li class="border p-3 rounded-lg">
                        <p class="font-medium">{{ $rating->wisata->nama }}</p>
                        <p>Rating: {{ $rating->rating }} ⭐</p>
                        <p class="text-sm italic text-gray-500">"{{ $rating->feedback }}"</p>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 italic">Belum ada review yang diberikan.</p>
        @endif
    </div>

    <div class="space-y-2">
        <h3 class="text-lg font-semibold text-gray-700">Edit Profil</h3>

        @if (session()->has('message'))
            <div class="p-3 bg-green-100 text-green-700 rounded-xl">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="updateProfile" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-600">Nama</label>
                <input type="text" id="name" wire:model="name" class="w-full border p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                <input type="email" id="email" wire:model="email" class="w-full border p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" />
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-600">Password Lama</label>
                <input type="password" id="current_password" wire:model.defer="current_password" class="w-full border p-2 rounded-lg" />
                @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <!-- Password Baru -->
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-600">Password Baru</label>
                <input type="password" id="new_password" wire:model.defer="new_password" class="w-full border p-2 rounded-lg" />
                @error('new_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <!-- Konfirmasi Password Baru -->
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-600">Konfirmasi Password Baru</label>
                <input type="password" id="new_password_confirmation" wire:model.defer="new_password_confirmation" class="w-full border p-2 rounded-lg" />
            </div>


            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan Perubahan</button>
        </form>
    </div>
</div>
