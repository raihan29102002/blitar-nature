<section class="bg-white py-12">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-semibold text-center text-gray-800 mb-8">Wisata Terpopuler</h2>

        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                @foreach ($wisataTerpopuler as $wisata)
                    <div class="swiper-slide">
                        <div class="bg-white shadow-md rounded-xl overflow-hidden">
                            <img src="{{ $wisata->media->first()->url ?? '/images/default.jpg' }}" alt="{{ $wisata->nama }}" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $wisata->nama }}</h3>
                                <p class="text-sm text-gray-600 mb-3 truncate">{{ $wisata->deskripsi }}</p>
                                <a href="{{ route('wisata.detail', $wisata->id) }}"
                                   class="inline-block px-4 py-2 bg-lime-600 text-white rounded-full hover:bg-lime-700 text-sm">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Navigasi -->
            <div class="swiper-button-next text-gray-700"></div>
            <div class="swiper-button-prev text-gray-700"></div>
        </div>
    </div>
</section>

<!-- SwiperJS Styles & Script (hanya sekali include di layout/master layout) -->
@push('scripts')
    <script>
        const swiper = new Swiper(".mySwiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            },
        });
    </script>
@endpush


