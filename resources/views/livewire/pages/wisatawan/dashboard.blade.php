<section class="relative bg-white overflow-hidden">
    <div class="relative h-screen w-full" x-data="{ currentIndex: 0, images: [
        '/storage/img/monte.jpg', '/storage/img/pantai.jpg', '/storage/img/teh.jpg', '/storage/img/gunung.jpg', '/storage/img/terjun.jpg'
    ], init() {
        setInterval(() => { this.currentIndex = (this.currentIndex + 1) % this.images.length }, 7000);
    } }" x-init="init()">
        <div class="absolute w-full z-50">
            @include('partials.navbar-wisatawan')
        </div>
        <template x-for="(image, index) in images" :key="index">
            <div x-show="currentIndex === index" x-transition:enter="transition-opacity duration-1000"
                x-transition:leave="transition-opacity duration-1000" class="absolute inset-0 bg-cover bg-center"
                :style="`background-image: url(${image});`">
            </div>
        </template>

        <div class="absolute inset-0 bg-black/50 z-10"></div>



        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 z-30">
            <img src="https://res.cloudinary.com/ddvtpgszb/image/upload/v1746929595/fiks_kt64q1.png" class="h-28 mb-4"
                alt="Visit Blitar Logo">
            <h1 class="text-white text-3xl sm:text-5xl font-bold mb-4 leading-tight drop-shadow-lg">
                Menjelajah Blitar, Lebih Mudah
            </h1>
            <p class="text-white text-lg max-w-2xl mb-6 drop-shadow-md">
                Temukan berbagai destinasi wisata terbaik di Kabupaten Blitar — mulai dari wisata sejarah, budaya,
                hingga alam.
            </p>
            <a href="{{ route('wisata') }}"
                class="inline-block bg-lime-500 text-white font-semibold px-6 py-3 rounded-full hover:bg-lime-600 transition">
                Lihat Destinasi
            </a>
        </div>
    </div>
    <section class="bg-white py-16">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Tentang Kabupaten Blitar</h2>
            <p class="text-gray-600 max-w-2xl mx-auto text-lg mb-8">
                Kabupaten Blitar dikenal sebagai tempat peristirahatan terakhir Bung Karno, dengan kekayaan sejarah,
                budaya, dan keindahan alam yang menawan. Jelajahi pesonanya dari pantai hingga pegunungan sejuk.
            </p>

            {{-- Video Profil --}}
            <div class="max-w-4xl mx-auto aspect-video">
                <iframe class="w-full h-full rounded-xl shadow-lg" src="https://www.youtube.com/embed/opAdrIUhO3w"
                    title="Video Profil Kabupaten Blitar" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </section>

    @include('partials.swiper-wisata-terpopuler')

    @if(isset($highlightWisata))
    <section class="bg-gray-100 py-16">
        <div class="container mx-auto px-6 grid md:grid-cols-2 gap-10 items-center">
            <div>
                <a href="{{ $highlightWisata->media->first()->url ?? '' }}" data-fancybox="galeri"
                    data-caption="{{ $highlightWisata->deskripsi ?? 'Foto Wisata' }}">
                    <img src="{{ $highlightWisata->media->first()->url ?? '' }}" alt="Highlight Wisata"
                        class="rounded-2xl w-full aspect-video object-cover aspect-square hover:scale-105 transition-transform duration-200">
                </a>

            </div>
            <div>
                <h3 class="text-3xl font-bold text-gray-800 mb-4">{{ $highlightWisata->nama }}</h3>
                <p class="text-gray-700 mb-6">{{ $highlightWisata->deskripsi }}</p>
                <a href="{{ route('wisata.detail', $highlightWisata->id) }}"
                    class="bg-lime-500 text-white px-6 py-3 rounded-full hover:bg-lime-600 transition">Lihat
                    Selengkapnya</a>
            </div>
        </div>
    </section>
    @endif

    @isset($gridFotos)
    @if($gridFotos->isNotEmpty())
    <section class="bg-white py-16" data-aos="fade-up">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8" data-aos="fade-down">Galeri Wisata</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($gridFotos as $foto)
                <div class="rounded-xl overflow-hidden shadow-sm hover:shadow-md transition" data-aos="zoom-in"
                    data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ $foto->url }}" data-fancybox="galeri"
                        data-caption="{{ $foto->deskripsi ?? 'Foto Wisata' }}">
                        <img src="{{ $foto->url }}" alt="{{ $foto->deskripsi ?? 'Foto Wisata' }}" loading="lazy"
                            class="w-full h-full object-cover aspect-square hover:scale-105 transition-transform duration-200">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @endisset
</section>