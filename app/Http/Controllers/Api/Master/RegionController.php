<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    use ApiResponse;

    public function provinces()
    {
        $data = \Illuminate\Support\Facades\Cache::rememberForever('provinces_all', function() {
            return Province::select('id', 'code', 'name')->orderBy('name')->get();
        });
        return $this->successResponse('Data Provinsi', $data);
    }

    public function cities(Request $request)
    {
        $provinceId = $request->province_id;
        if (!$provinceId) return $this->successResponse('Data Kota', []);

        $data = \Illuminate\Support\Facades\Cache::rememberForever("cities_prov_{$provinceId}", function() use ($provinceId) {
            return City::select('id', 'province_id', 'code', 'name', 'type')
                ->where('province_id', $provinceId)
                ->orderBy('name')
                ->get();
        });
        
        return $this->successResponse('Data Kota', $data);
    }

    public function districts(Request $request)
    {
        $cityId = $request->city_id;
        if (!$cityId) return $this->successResponse('Data Kecamatan', []);

        $city = City::find($cityId);
        if (!$city) return $this->errorResponse('Kota tidak ditemukan', null, 404);

        // Cek database dulu
        $districts = District::where('city_id', $cityId)->orderBy('name')->get();

        // Jika kosong, ambil Paksa dari API (On-The-Fly)
        if ($districts->isEmpty()) {
            try {
                $response = \Illuminate\Support\Facades\Http::withOptions(['verify' => false, 'timeout' => 10])
                    ->get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$city->code}.json");
                
                if ($response->successful()) {
                    $apiData = $response->json();
                    $insertData = array_map(fn($d) => [
                        'city_id' => $cityId,
                        'code' => $d['id'],
                        'name' => strtoupper($d['name']),
                        'created_at' => now(),
                        'updated_at' => now()
                    ], $apiData);
                    
                    District::insert($insertData);
                    $districts = District::where('city_id', $cityId)->orderBy('name')->get();
                }
            } catch (\Exception $e) {
                // Quiet fail, return empty
            }
        }

        return $this->successResponse('Data Kecamatan', $districts);
    }
}
