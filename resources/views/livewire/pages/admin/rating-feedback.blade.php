<div>
    <h3>Rating Wisata: {{ number_format($averageRating, 1) }} / 5</h3>

    @foreach ($reviews as $review)
        <div class="mb-2 p-3 border rounded">
            <strong>{{ $review->user->name }}</strong>
            <p>Rating: {{ $review->rating }} / 5</p>
            <p>{{ $review->feedback }}</p>
            <small>{{ $review->created_at->diffForHumans() }}</small>

            @if ($review->response_admin)
                <p><strong>Respon Admin:</strong> {{ $review->response_admin }}</p>
            @endif

            @if (Auth::user() && Auth::user()->role === 'admin')
                <textarea wire:model="responseAdmin.{{ $review->id }}" class="border p-2 rounded w-full" placeholder="Tanggapan Admin"></textarea>
                <button wire:click="submitResponse({{ $review->id }})" class="bg-green-500 text-white px-4 py-2 rounded mt-2">
                    Kirim Tanggapan
                </button>
            @endif
        </div>
    @endforeach

    @auth
        <form wire:submit.prevent="submit">
            <label>Berikan Rating:</label>
            <input type="number" wire:model="rating" min="1" max="5" required class="border p-2 rounded">
            
            <label>Ulasan:</label>
            <textarea wire:model="feedback" class="border p-2 rounded"></textarea>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Kirim</button>
        </form>

        @if (session()->has('error'))
            <p class="text-red-500">{{ session('error') }}</p>
        @endif

        @if (session()->has('success'))
            <p class="text-green-500">{{ session('success') }}</p>
        @endif
    @endauth
</div>
