<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="https://res.cloudinary.com/ddvtpgszb/image/upload/v1746929596/fiks_bg_dzgsh3.png">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
    </style>

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <main>
            {{ $slot }}
        </main>
        <footer class="bg-gray-900 text-white mt-12">
            <div class="container mx-auto px-4 py-10">
                <div class="flex flex-wrap justify-between">
                    <div class="mb-6 max-w-sm">
                        <h2 class="text-xl font-bold mb-2">Tentang VisitBlitar</h2>
                        <p class="text-gray-400 text-sm">
                            VisitBlitar adalah platform informasi wisata Kabupaten Blitar yang mempermudah wisatawan
                            menemukan destinasi terbaik.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold mb-2">Tautan</h2>
                        <ul class="text-gray-400 space-y-2 text-sm">
                            <li><a href="{{ route('wisatawan.dashboard') }}" class="hover:text-white">Beranda</a></li>
                            <li><a href="{{ route('wisata') }}" class="hover:text-white">Wisata</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-white">Login</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-700 pt-4 text-center text-sm text-gray-500">
                    © {{ date('Y') }} VisitBlitar. All rights reserved.
                </div>
            </div>
        </footer>

    </div>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        AOS.init({
            once: true, // animasi hanya muncul sekali
            duration: 800, // durasi animasi (ms)
            easing: 'ease-in-out', // tipe transisi
        });
    </script>
    @stack('scripts')
</body>

</html>