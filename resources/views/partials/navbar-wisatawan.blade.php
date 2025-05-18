<nav class="sticky top-0 py-0 bg-gray-900/30 backdrop-blur-sm z-30">
    <div class="container mx-auto px-4 py-3">
        <div class="relative flex items-center justify-between ms-20">
            <a class="inline-block" href="{{ route('wisatawan.dashboard') }}">
                <img class="h-16 w-auto" src="https://res.cloudinary.com/ddvtpgszb/image/upload/v1746929595/fiks_kt64q1.png" alt="Logo Blitar Nature Explore" />
            </a>

            <ul class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 hidden md:flex">
                <li class="mr-8">
                    <a class="text-white hover:text-lime-500 font-medium text-lg"
                       href="{{ route('wisatawan.dashboard') }}">Beranda</a>
                </li>
                <li class="mr-8">
                    <a class="text-white hover:text-lime-500 font-medium text-lg"
                       href="{{ route('wisata') }}">Wisata</a>
                </li>
            </ul>

            <div class="flex items-center justify-end">
                <div class="hidden md:block relative" x-data="{ open: false }">
                    @auth
                        <button @click="open = !open"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white border border-white rounded-full hover:text-lime-400">
                            {{ Auth::user()->name }}
                            <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414L10 13.414 5.293 8.707a1 1 0 010-1.414z"
                                      clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.outside="open = false"
                             class="absolute right-0 mt-2 w-40 bg-white text-gray-800 rounded-lg shadow-lg z-50">
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">Panel Admin</a>
                            @endif
                            <a href="{{ route('profil') }}" class="block px-4 py-2 hover:bg-gray-100">Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="block w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    @else
                        <a class="group inline-flex py-2.5 px-4 text-sm font-medium text-white hover:text-lime-400 border border-white hover:bg-white rounded-full transition duration-200"
                           href="{{ route('login') }}">
                            <span class="mr-2">Login</span>
                            <span class="transform group-hover:translate-x-0.5 transition-transform duration-200">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M4.75 10H15.25" stroke="currentColor" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M10 4.75L15.25 10L10 15.25" stroke="currentColor" stroke-width="1.5"
                                          stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </span>
                        </a>
                    @endauth
                </div>

                <button class="md:hidden text-white hover:text-lime-500"
                        x-on:click="mobileNavOpen = !mobileNavOpen">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <path d="M5.2 23.2H26.8M5.2 16H26.8M5.2 8.8H26.8"
                              stroke="currentColor" stroke-width="1.5"
                              stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
