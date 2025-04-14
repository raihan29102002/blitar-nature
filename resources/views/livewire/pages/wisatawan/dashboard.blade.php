<section class="relative bg-teal-900 overflow-hidden min-h-screen"
         x-data="{
            mobileNavOpen: false,
            currentIndex: 0,
            images: [
                '/storage/img/monte.jpg',
                '/storage/img/pantai.jpg',
                '/storage/img/teh.jpg',
                '/storage/img/gunung.jpg',
                '/storage/img/terjun.jpg'
            ],
            init() {
                setInterval(() => {
                    this.currentIndex = (this.currentIndex + 1) % this.images.length;
                }, 6000);
            }
         }"
         x-init="init()">
         <template x-for="(image, index) in images" :key="index">
            <div 
                x-show="currentIndex === index" 
                x-transition:enter="transition-opacity duration-1000"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-1000"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute top-0 left-0 w-full h-full bg-cover bg-center z-0"
                :style="`background-image: url(${image});`">
            </div>
        </template>
        <div class="absolute inset-0 bg-black opacity-40 z-10"></div>
    <nav class="sticky top-0 py-6 bg-gray-900/30 backdrop-blur-sm z-30">
        <div class="container mx-auto px-4">
            <div class="relative flex items-center justify-between">
                <a class="inline-block" href="{{ route('home') }}">
                    {{-- <img class="h-8" src="images/logo-white.svg" alt="Logo Blitar Nature Explore" /> --}}
                </a>

                <!-- Desktop Nav -->
                <ul class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 hidden md:flex">
                    <li class="mr-8">
                        <a class="text-white hover:text-lime-500 font-medium text-lg" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="mr-8">
                        <a class="text-white hover:text-lime-500 font-medium text-lg" href="{{ route('wisata') }}">Wisata</a>
                    </li>
                </ul>

                <!-- Login Button & Hamburger -->
                <div class="flex items-center justify-end">
                    <div class="hidden md:block relative" x-data="{ open: false }">
                        @auth
                            <button @click="open = !open" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white border border-white rounded-full hover:text-lime-400">
                                {{ Auth::user()->name }}
                                <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414L10 13.414 5.293 8.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-40 bg-white text-gray-800 rounded-lg shadow-lg z-50">
                                <a href="{{ route('profil') }}" class="block px-4 py-2 hover:bg-gray-100">Profil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        @else
                            <a class="group inline-flex py-2.5 px-4 text-sm font-medium text-white hover:text-lime-400 border border-white hover:bg-white rounded-full transition duration-200" href="{{ route('login') }}">
                                <span class="mr-2">Login</span>
                                <span class="transform group-hover:translate-x-0.5 transition-transform duration-200">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M4.75 10H15.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M10 4.75L15.25 10L10 15.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </a>
                        @endauth
                    </div>
                    <button class="md:hidden text-white hover:text-lime-500" x-on:click="mobileNavOpen = !mobileNavOpen">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <path d="M5.2 23.2H26.8M5.2 16H26.8M5.2 8.8H26.8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative flex items-center justify-center min-h-[calc(100vh-4rem)] px-4 z-20">
        <div class="container mx-auto px-4">
            <div class="max-w-lg xl:max-w-xl mx-auto text-center bg-gray-900/50 p-6 rounded-xl">
                <h1 class="font-heading text-2xl sm:text-4xl xl:text-5xl tracking-tight text-white mb-6">
                    Jelajahi Keindahan Alam Blitar
                </h1>
                <p class="max-w-md xl:max-w-none text-base sm:text-lg text-white opacity-80 mb-8">
                    Temukan destinasi wisata alam terbaik di Kabupaten Blitar — dari air terjun yang memukau hingga hutan pinus yang menenangkan, semua dalam satu platform interaktif dan informatif.
                </p>
                <a class="inline-flex py-3 px-5 text-base sm:text-lg font-medium text-lime-500 border border-lime-500 hover:border-white bg-transparent hover:bg-lime-500 hover:text-white rounded-full transition duration-200" href="{{ route('wisata') }}">
                    Lihat Destinasi
                </a>
            </div>
        </div>
    </div>
    

    <!-- Mobile Navigation -->
    <div class="fixed top-0 left-0 bottom-0 w-full xs:w-5/6 xs:max-w-md z-50" :class="{'block': mobileNavOpen, 'hidden': !mobileNavOpen}">
        <div class="fixed inset-0 bg-teal-900 opacity-20" x-on:click="mobileNavOpen = !mobileNavOpen"></div>
        <nav class="relative flex flex-col py-7 px-10 w-full h-full bg-gray-900/30 backdrop-blur-sm text-white overflow-y-auto">
            <div class="flex items-center justify-between">
                <a class="inline-block" href="{{ route('home') }}">
                    {{-- <img class="h-8" src="images/logo.svg" alt="Mobile Logo" /> --}}
                </a>
                <div class="flex items-center" x-data="{ open: false }">
                    @auth
                        <div class="relative mr-6">
                            <button @click="open = !open" class="py-2.5 px-4 text-sm font-medium text-white hover:text-lime-500 border border-white hover:bg-white rounded-full transition duration-200 flex items-center">
                                {{ Auth::user()->name }}
                                <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414L10 13.414 5.293 8.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.outside="open = false" class="absolute right-0 mt-2 w-40 bg-white text-gray-800 rounded-lg shadow-lg z-50">
                                <a href="{{ route('profil') }}" class="block px-4 py-2 hover:bg-gray-100">Profil</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a class="py-2.5 px-4 mr-6 text-sm font-medium text-white hover:text-lime-500 border border-white hover:bg-white rounded-full transition duration-200" href="{{ route('login') }}">Login</a>
                    @endauth
                
                    <button x-on:click="mobileNavOpen = !mobileNavOpen">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <path d="M23.2 8.8L8.8 23.2M8.8 8.8L23.2 23.2" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>                
            </div>

            <!-- Mobile Links -->
            <div class="pt-20 pb-12 mb-auto">
                <ul class="flex-col">
                    <li class="mb-6">
                        <a class="text-white hover:text-lime-400 font-medium" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="mb-6">
                        <a class="text-white hover:text-lime-400 font-medium" href="{{ route('wisata') }}">Wisata</a>
                    </li>
                </ul>
            </div>

        </nav>
    </div>
</section>
