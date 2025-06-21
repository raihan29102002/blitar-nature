<nav x-data="{ open: false }" class="bg-gray-100 shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('wisatawan.dashboard') }}">
                    <img src="https://res.cloudinary.com/ddvtpgszb/image/upload/v1746929595/fiks_kt64q1.png"
                         alt="Blitar Nature Explore Logo"
                         class="h-16 w-auto">
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('wisatawan.dashboard') }}" class="text-gray-700 hover:text-green-700 transition">Beranda</a>
                <a href="{{ route('wisata') }}" class="text-gray-700 hover:text-green-700 transition">Wisata</a>
                @auth
                    <a href="{{ route('profil') }}" class="text-gray-700 hover:text-green-700 transition">Profil</a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-700 transition">Profil</a>
                @endauth
            </div>

            <div class="hidden sm:flex items-center">
                @auth
                    <x-user-dropdown />
                @else
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 border border-black rounded-full hover:text-green-700 transition">
                        <span class="mr-2">Login</span>
                        <svg width="20" height="20" fill="none" viewBox="0 0 20 20">
                            <path d="M4.75 10H15.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 4.75L15.25 10L10 15.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                @endauth
            </div>

            <div class="md:hidden flex items-center">
                <button @click="open = !open" class="text-gray-600 hover:text-green-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-transition class="md:hidden px-4 pb-4">
        <a href="{{ route('wisatawan.dashboard') }}" class="block py-2 text-gray-700 hover:text-green-700">Beranda</a>
        <a href="{{ route('wisata') }}" class="block py-2 text-gray-700 hover:text-green-700">Wisata</a>
        @auth
            <a href="{{ route('profil') }}" class="block py-2 text-gray-700 hover:text-green-700">Profil</a>
        @else
            <a href="{{ route('login') }}" class="block py-2 text-gray-700 hover:text-green-700">Profil</a>
        @endauth

        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800" x-data="{ name: '{{ Auth::user()->name }}' }"
                     x-text="name" x-on:profile-updated.window="name = $event.detail.name">
                </div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">Panel Admin</a>
                @endif
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left">
                        <x-responsive-nav-link>
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </button>
                </form>
            </div>
        </div>
        @else {{-- Jika belum login, tampilkan tombol login di mobile menu --}}
        <div class="pt-4 pb-1 border-t border-gray-200">
             <a href="{{ route('login') }}"
               class="block py-2 text-gray-700 hover:text-green-700">
                Login
            </a>
        </div>
        @endauth
    </div>
</nav>