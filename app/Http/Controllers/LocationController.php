<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function getCountries()
    {
        $response = Http::get('https://countriesnow.space/api/v0.1/countries/positions');
        return ;
    }

    public function getStates(Request $request)
    {
        $response = Http::post('https://countriesnow.space/api/v0.1/countries/states', [
            'country' => $request->country,
        ]);

        return $response->json();
    }

    public function getCities(Request $request)
    {
        $response = Http::post('https://countriesnow.space/api/v0.1/countries/state/cities', [
            'country' => $request->country,
            'state' => $request->state,
        ]);

        return $response->json();
    }
}
