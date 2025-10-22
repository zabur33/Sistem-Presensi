<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ReverseGeocodeController extends Controller
{
    public function lookup(Request $request)
    {
        $data = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $lat = $data['lat'];
        $lng = $data['lng'];
        $apiKey = env('GOOGLE_MAPS_KEY');
        if (!$apiKey) {
            return response()->json(['message' => 'Missing Google Maps key'], 500);
        }

        $url = 'https://maps.googleapis.com/maps/api/geocode/json';
        $resp = Http::get($url, [
            'latlng' => $lat . ',' . $lng,
            'language' => 'id',
            'key' => $apiKey,
        ]);

        if (!$resp->ok()) {
            return response()->json(['message' => 'Geocode request failed'], 502);
        }

        $json = $resp->json();
        $address = null;
        if (($json['status'] ?? '') === 'OK' && !empty($json['results'])) {
            $address = $json['results'][0]['formatted_address'] ?? null;
        }

        return response()->json([
            'address' => $address,
        ]);
    }
}
