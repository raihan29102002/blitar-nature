
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Blitar Nature Explore') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['node_modules/@flaticon/flaticon-uicons/css/all/all.css'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


        @livewireStyles

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 w-full">
            <livewire:layout.admin />
            <div class="flex min-h-screen bg-gray-100">
                <div x-data="{ open: true }" class="flex flex-col w-64 bg-white border-r dark:bg-gray-800 dark:border-gray-700" :class="{ 'w-64': open, 'w-20': !open }">
                    <button @click="open = !open" class="p-4 focus:outline-none">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
                        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
                            <ul class="space-y-4 font-medium dark:text-white">
                                <li>
                                    <a href="/admin/dashboard" class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                        <i class="fi fi-rr-home mr-2"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/wisata" class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                        <i class="fi fi-rr-map-marker mr-2"></i> Data Wisata
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/fasilitas" class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                        <i class="fi fi-rr-tools mr-2"></i> Data Fasilitas
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/pengunjung" class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                        <i class="fi fi-rr-users mr-2"></i> Data Pengunjung
                                    </a>
                                </li>
                                <li>
                                    <a href="/admin/rating" class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                        <i class="fi fi-rr-users mr-2"></i> Data Review Pengunjung
                                    </a>
                                </li>
                            </ul>
                            <div class="absolute bottom-4 left-0 w-full px-3">
                                <ul class="mt-4 space-y-4 font-medium dark:text-white border-t border-gray-300 dark:border-gray-600 pt-4">
                                    <li>
                                        <a href="/admin/akun" class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                            <i class="fi fi-rr-user mr-3"></i> Menu Akun
                                        </a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center p-2 text-red-500 hover:bg-red-100 rounded">
                                                <i class="fi fi-rr-exit mr-3"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>

                <main class="flex-1 w-full min-h-screen p-16 bg-white shadow-md">
                    {{ $slot }}
                </main>
            </div>
        </div>
        @livewireScripts
    </body>
</html>
