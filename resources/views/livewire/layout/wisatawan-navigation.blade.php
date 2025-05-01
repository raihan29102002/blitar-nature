
<nav x-data="{ open: false }" class="bg-grey shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('wisatawan.dashboard') }}" class="text-xl font-bold text-green-700">
                    Blitar Nature Explore
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex space-x-6">
                <a href="{{ route('wisatawan.dashboard') }}" class="text-gray-700 hover:text-green-700 transition">Beranda</a>
                <a href="{{ route('wisata') }}" class="text-gray-700 hover:text-green-700 transition">Wisata</a>
                <a href="{{ route('profil') }}" class="text-gray-700 hover:text-green-700 transition">Profil</a>
            </div>

            <!-- Hamburger Menu Button -->
            <div class="md:hidden">
                <button @click="open = !open" class="text-gray-600 hover:text-green-700 focus:outline-none focus:text-green-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <template x-show="!open">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16" />
                        </template>
                        <template x-show="open">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12" />
                        </template>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" class="md:hidden px-4 pb-4">
        <a href="{{ route('wisatawan.dashboard') }}" class="block py-2 text-gray-700 hover:text-green-700">Beranda</a>
        <a href="{{ route('wisata') }}" class="block py-2 text-gray-700 hover:text-green-700">Wisata</a>
        <a href="{{ route('profil') }}" class="block py-2 text-gray-700 hover:text-green-700">Profil</a>
    </div>
</nav>