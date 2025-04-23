<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CountryController extends Controller
{
    
        // 1. Get All Countries
        public function getCountries()
        {
            $response = Http::get('https://countriesnow.space/api/v0.1/countries/positions');
            if ($response->successful()) {
                return response()->json($response->json()['data']);
            }
            return response()->json(['error' => 'Unable to fetch countries'], 500);
        }
    
        // 2. Get States based on country
        public function getStates(Request $request)
        {
            $response = Http::post('https://countriesnow.space/api/v0.1/countries/states', [
                'country' => $request->country
            ]);
    
            if ($response->successful()) {
                return response()->json($response->json()['data']['states']);
            }
    
            return response()->json(['error' => 'Unable to fetch states'], 500);
        }
    
        // 3. Get Cities based on state and country
        public function getCities(Request $request)
        {
            $response = Http::post('https://countriesnow.space/api/v0.1/countries/state/cities', [
                'country' => $request->country,
                'state' => $request->state
            ]);
    
            if ($response->successful()) {
                return response()->json($response->json()['data']);
            }
    
            return response()->json(['error' => 'Unable to fetch cities'], 500);
        }
}
