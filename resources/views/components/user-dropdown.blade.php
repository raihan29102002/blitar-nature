@props(['name' => Auth::user()->name, 'email' => Auth::user()->email, 'role' => Auth::user()->role])

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open"
        class="inline-flex items-center px-4 py-2 text-sm font-medium text-grey-700 border border-black rounded-full hover:text-green-700">
        {{ $name }}
        <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414L10 13.414 5.293 8.707a1 1 0 010-1.414z"
                clip-rule="evenodd" />
        </svg>
    </button>
    <div x-show="open" @click.outside="open = false"
        class="absolute right-0 mt-2 w-44 bg-white text-gray-800 rounded-lg shadow-lg z-50 transition ease-out duration-150"
        x-transition>
        @if ($role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">Panel Admin</a>
        @endif
        <a href="{{ route('profil') }}" class="block px-4 py-2 hover:bg-gray-100">Profil</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100">Logout</button>
        </form>
    </div>
</div>
