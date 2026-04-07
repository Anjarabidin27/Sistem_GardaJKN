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
        $user = auth()->user();
        
        // Audit: Filter by User Context (KC Staff only see their Province)
        if ($user && $user->kantor_cabang_id) {
            $kc = $user->kantorCabang;
            if ($kc && $kc->province_id) {
                $data = Province::where('id', $kc->province_id)->get(['id', 'code', 'name']);
                return $this->successResponse('Data Provinsi (Filtered)', $data);
            }
        }

        $data = \Illuminate\Support\Facades\Cache::rememberForever('provinces_all', function() {
            return Province::select('id', 'code', 'name')->orderBy('name')->get();
        });
        return $this->successResponse('Data Provinsi', $data);
    }

    public function cities(Request $request)
    {
        $user = auth()->user();
        $provinceId = $request->province_id;

        // Audit: Filter by User Context (KC Staff only see their City)
        if ($user && $user->kantor_cabang_id) {
            $kc = $user->kantorCabang;
            if ($kc && $kc->city_id) {
                $data = City::where('id', $kc->city_id)->get(['id', 'province_id', 'code', 'name', 'type']);
                return $this->successResponse('Data Kota (Filtered)', $data);
            }
        }

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
        
        // If no cityId provided but user is a KC staff, auto-detect their city
        if (!$cityId) {
            $user = auth()->user();
            if ($user && $user->kantor_cabang_id) {
                $cityId = $user->kantorCabang?->city_id;
            }
        }

        if (!$cityId) return $this->successResponse('Data Kecamatan', []);

        $city = City::find($cityId);
        if (!$city) return $this->errorResponse('Kota tidak ditemukan', null, 404);

        $districts = District::where('city_id', $cityId)->orderBy('name')->get();

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
            } catch (\Exception $e) { }
        }

        return $this->successResponse('Data Kecamatan', $districts);
    }

    public function getContext()
    {
        $user = auth()->user();
        if (!$user) return $this->errorResponse('Unauthorized', null, 401);

        $kc = null;
        
        // 1. Try AdminUser KantorCabang relationship
        if ($user->kantor_cabang_id) {
            $kc = $user->kantorCabang;
        } 
        // 2. Try matching by string name (Petugas MAKASSAR fallback)
        elseif ($user->kantor_cabang) {
            $kc = \App\Models\KantorCabang::where('name', 'LIKE', '%' . $user->kantor_cabang . '%')->first();
        }
        // 3. Try matching by Member City (For New Registered Members promoted to Staff)
        elseif ($user->city_id) {
            $kc = \App\Models\KantorCabang::where('city_id', $user->city_id)->first();
        }

        return $this->successResponse('User Context', [
            'role' => $user->role,
            'unit_name' => $user->kantor_cabang ?? ($kc?->name ?? 'NASIONAL'),
            'kantor_cabang' => [
                'id' => $kc?->id,
                'name' => $kc?->name,
                'province_id' => $kc?->province_id ?? $user->province_id,
                'city_id' => $kc?->city_id ?? $user->city_id,
            ],
            'auto_fill' => (bool)(($kc?->province_id && $kc?->city_id) || ($user->province_id && $user->city_id))
        ]);
    }
}
