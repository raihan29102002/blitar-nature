<div class="p-4 bg-white dark:bg-gray-800 rounded-2xl shadow-md">
    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Top 3 Wisata Berdasarkan Rating</h2>
  
    <div class="space-y-3">
      @foreach($topWisata as $wisata)
        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm">
          <div class="flex items-center space-x-3">
            <img src="{{ $wisata->thumbnail_url }}" alt="{{ $wisata->nama }}" class="w-12 h-12 rounded-full object-cover">
            <div>
              <p class="font-medium text-gray-800 dark:text-gray-100">{{ $wisata->nama }}</p>
              <p class="text-sm text-gray-500 dark:text-gray-300">Rating: {{ number_format($wisata->average_rating, 1) }} / 5</p>
            </div>
          </div>
          <div class="text-yellow-400 text-lg">
            @for ($i = 1; $i <= 5; $i++)
              @if ($i <= floor($wisata->average_rating))
                ★
              @else
                ☆
              @endif
            @endfor
          </div>
        </div>
      @endforeach
    </div>
</div>