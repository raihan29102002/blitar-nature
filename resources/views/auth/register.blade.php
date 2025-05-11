<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-teal-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-40 z-0"></div>
        <div class="relative z-10 w-full max-w-6xl grid md:grid-cols-2 gap-10 p-6 text-white">
            <div class="flex flex-col justify-center">
                <h2 class="text-3xl lg:text-5xl font-bold leading-tight">
                    Selamat Datang
                </h2>
                <p class="text-sm mt-6 text-slate-500 leading-relaxed">
                    Daftar Sekarang untuk menjelajahi keanekaragaman wisata di Kabupaten Blitar secara eksklusif.
                </p>
                <p class="text-sm mt-12 text-slate-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-lime-400 font-medium hover:underline ml-1">Masuk disini</a>
                </p>
            </div>

            <div class="bg-white bg-opacity-10 backdrop-blur-md rounded-xl p-6 shadow-lg">
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <h3 class="text-white lg:text-3xl text-2xl font-bold mb-8">
                        Daftar
                    </h3>

                    <!-- Name -->
                    <div>
                        <label for="name" class='text-sm text-white font-medium mb-2 block'>Nama Lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan Nama Lengkap"
                               class="bg-white bg-opacity-20 w-full text-sm text-white px-4 py-3 rounded-md outline-0 border border-white placeholder-white focus:border-lime-400 focus:bg-transparent" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class='text-sm text-white font-medium mb-2 block'>Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Masukkan Email"
                               class="bg-white bg-opacity-20 w-full text-sm text-white px-4 py-3 rounded-md outline-0 border border-white placeholder-white focus:border-lime-400 focus:bg-transparent" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class='text-sm text-white font-medium mb-2 block'>Password</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password" placeholder="Masukkan Password"
                               class="bg-white bg-opacity-20 w-full text-sm text-white px-4 py-3 rounded-md outline-0 border border-white placeholder-white focus:border-lime-400 focus:bg-transparent" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class='text-sm text-white font-medium mb-2 block'>Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Masukkan kembali Password"
                               class="bg-white bg-opacity-20 w-full text-sm text-white px-4 py-3 rounded-md outline-0 border border-white placeholder-white focus:border-lime-400 focus:bg-transparent" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Register Button -->
                    <div class="!mt-12">
                        <button type="submit"
                                class="w-full py-2.5 px-4 text-sm font-semibold rounded text-white bg-transparent border border-lime-400 hover:bg-lime-500 hover:text-white transition duration-200">
                            Daftar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
