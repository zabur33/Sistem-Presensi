<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FreeGeocodeController extends Controller
{
    public function lookup(Request $request)
    {
        $data = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $lat = $data['lat'];
        $lng = $data['lng'];

        // Nominatim (OpenStreetMap) - FREE
        $url = 'https://nominatim.openstreetmap.org/reverse';
        $params = [
            'format' => 'json',
            'lat' => $lat,
            'lon' => $lng,
            'zoom' => 18,
            'addressdetails' => 1,
            'accept-language' => 'id',
        ];

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'SistemPresensi/1.0'
            ])->get($url, $params);

            if (!$response->successful()) {
                return response()->json(['address' => null]);
            }

            $data = $response->json();
            
            $address = null;
            if (isset($data['display_name'])) {
                $address = $data['display_name'];
            }

            return response()->json([
                'address' => $address,
            ]);
        } catch (\Exception $e) {
            return response()->json(['address' => null]);
        }
    }
}
