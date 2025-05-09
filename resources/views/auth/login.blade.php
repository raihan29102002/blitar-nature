<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-teal-900 relative overflow-hidden">
        <!-- Overlay hitam transparan -->
        <div class="absolute inset-0 bg-black opacity-40 z-0"></div>

        <div class="relative z-10 w-full max-w-6xl grid md:grid-cols-2 gap-10 p-6 text-white">
            <div class="flex flex-col justify-center">
                <h2 class="text-3xl lg:text-5xl font-bold leading-tight">
                    Selamat Datang Kembali!
                </h2>
                <p class="text-base mt-6 opacity-80 leading-relaxed">
                    Masuk untuk menjelajahi keindahan alam Kabupaten Blitar secara eksklusif.
                </p>
                <p class="text-sm mt-12 opacity-80">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-lime-400 hover:underline font-medium ml-1">Daftar di sini</a>
                </p>
            </div>

            <div class="bg-white bg-opacity-10 backdrop-blur-md rounded-xl p-6 shadow-lg">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block mb-1 font-medium">Email</label>
                        <input id="email" type="email" name="email" required autofocus
                            class="w-full px-4 py-2 rounded-md bg-white bg-opacity-20 border border-white placeholder-white text-white focus:ring-2 focus:ring-lime-400 focus:outline-none">
                    </div>

                    <div>
                        <label for="password" class="block mb-1 font-medium">Password</label>
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-2 rounded-md bg-white bg-opacity-20 border border-white placeholder-white text-white focus:ring-2 focus:ring-lime-400 focus:outline-none">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="form-checkbox text-lime-500">
                            <span class="ml-2 text-sm">Ingat saya</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm text-lime-400 hover:underline">Lupa Password?</a>
                    </div>

                    <button type="submit"
                        class="w-full py-2 px-4 rounded-full bg-transparent border border-lime-400 text-lime-400 hover:bg-lime-500 hover:text-white transition duration-200 font-medium">
                        Masuk
                    </button>
                    <div class="my-4 flex items-center gap-4">
                        <hr class="w-full border-slate-300" />
                        <p class="text-sm text-slate-800 dark:text-white text-center">Atau</p>
                        <hr class="w-full border-slate-300" />
                    </div>
    
                    <!-- Social Icons -->
                    <div class="space-x-6 flex justify-center">
                        <!-- Google, Facebook, etc. Placeholder -->
                        <a href="{{ url('/auth/google') }}"
                        class="flex items-center justify-center gap-2 bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-lg shadow hover:bg-gray-100">
                            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-5 h-5" alt="Google Logo">
                            <span>Masuk dengan Google</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
