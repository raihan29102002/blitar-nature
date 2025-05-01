<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class GoogleMapsHelper
{
    public static function getDistance($originLat, $originLon, $destinationLat, $destinationLon)
    {
        $client = new Client();
        $apiKey = config('services.google_maps.key');
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$originLat},{$originLon}&destinations={$destinationLat},{$destinationLon}&key={$apiKey}";

        $response = $client->get($url);
        $data = json_decode($response->getBody()->getContents(), true);

        if ($data['status'] == 'OK') {
            $distance = $data['rows'][0]['elements'][0]['distance']['text'];
            return $distance;
        } else {
            return 'Error calculating distance';
        }
    }
}
