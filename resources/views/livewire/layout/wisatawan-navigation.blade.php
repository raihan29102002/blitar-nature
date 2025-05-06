<nav x-data="{ open: false }" class="bg-grey shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <a href="{{ route('wisatawan.dashboard') }}" class="text-xl font-bold text-green-700">
                    Blitar Nature Explore
                </a>
            </div>
            <div class="hidden md:flex space-x-6">
                <a href="{{ route('wisatawan.dashboard') }}"
                    class="text-gray-700 hover:text-green-700 transition">Beranda</a>
                <a href="{{ route('wisata') }}" class="text-gray-700 hover:text-green-700 transition">Wisata</a>
                <a href="{{ route('profil') }}" class="text-gray-700 hover:text-green-700 transition">Profil</a>
            </div>
            <div class="hidden sm:flex sm:items-center sm:ms-6 relative" x-data="{ open: false }">
                @auth
                <x-user-dropdown />
                @else
                <a class="inline-flex items-center px-4 py-2 text-sm font-medium text-grey-700 border border-black rounded-full hover:text-green-700"
                    href="{{ route('login') }}">
                    <span class="mr-2">Login</span>
                    <span class="transform group-hover:translate-x-0.5 transition-transform duration-200">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M4.75 10H15.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"></path>
                            <path d="M10 4.75L15.25 10L10 15.25" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </span>
                </a>
                @endauth
            </div>
            <div class="md:hidden">
                <button @click="open = !open"
                    class="text-gray-600 hover:text-green-700 focus:outline-none focus:text-green-700">
                    <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414L10 13.414 5.293 8.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div x-show="open" class="md:hidden px-4 pb-4">
        <a href="{{ route('wisatawan.dashboard') }}" class="block py-2 text-gray-700 hover:text-green-700">Beranda</a>
        <a href="{{ route('wisata') }}" class="block py-2 text-gray-700 hover:text-green-700">Wisata</a>
        <a href="{{ route('profil') }}" class="block py-2 text-gray-700 hover:text-green-700">Profil</a>
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
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">Panel
                    Admin</a>
                @endif
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-start">
                        <x-responsive-nav-link>
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>

</nav>