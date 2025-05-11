<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;

class GoogleMapsController extends Controller
{
    public function getUserLocation(Request $request)
    {
        return response()->json([
            'lat' => $request->lat,
            'lng' => $request->lng
        ]);
    }

    public function hitungJarak(Request $request)
    {
        $request->validate([
            'user_lat' => 'required|numeric',
            'user_lng' => 'required|numeric',
            'destinations' => 'required|string',
        ]);
    
        $userLat = $request->input('user_lat');
        $userLng = $request->input('user_lng');
        $destinations = $request->input('destinations');

        $apiKey = config('services.google_maps.key');
        $response = Http::get(
            "https://maps.googleapis.com/maps/api/distancematrix/json?" .
            "origins={$userLat},{$userLng}&" .
            "destinations={$destinations}&key={$apiKey}"
        );

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => 'Failed to get distance data',
                'message' => $response->json()
            ], 500);
        }
    }

    /**
     * Calculate distances for a collection of wisata
     *
     * @param Collection $wisatas
     * @param float $userLat
     * @param float $userLng
     * @return Collection
     */
    public function calculateDistances(Collection $wisatas, float $userLat, float $userLng): Collection
    {
        $validWisatas = $wisatas->filter(function ($wisata) {
            return !is_null($wisata->koordinat_x) && !is_null($wisata->koordinat_y);
        });

        if ($validWisatas->isEmpty()) {
            \Log::warning('Tidak ada wisata dengan koordinat valid untuk dihitung jarak');
            return $wisatas;
        }

        $apiKey = config('services.google_maps.key');
        $maxBatchSize = 25;
        $batched = $validWisatas->chunk($maxBatchSize);
        
        $allSuccessful = true;

        foreach ($batched as $chunk) {
            $destinations = $chunk->map(fn($w) => "{$w->koordinat_x},{$w->koordinat_y}")->implode('|');
            
            $response = Http::get("https://maps.googleapis.com/maps/api/distancematrix/json", [
                'origins' => "{$userLat},{$userLng}",
                'destinations' => $destinations,
                'key' => $apiKey,
            ]);

            if (!$response->successful()) {
                \Log::error('Gagal mendapatkan jarak dari API', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
                $allSuccessful = false;
                continue;
            }

            $data = $response->json();
            $elements = $data['rows'][0]['elements'] ?? [];

            foreach ($chunk as $index => $wisata) {
                $element = $elements[$index] ?? null;

                if ($element && ($element['status'] ?? '') === 'OK') {
                    $wisata->jarak = $element['distance']['value'] / 1000; // KM
                } else {
                    $wisata->jarak = null;
                }
            }
        }

        if (!$allSuccessful) {
            \Log::warning('Tidak semua permintaan API berhasil');
        }

        return $wisatas;
    }

}