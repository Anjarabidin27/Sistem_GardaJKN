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

    public function provinces(Request $request)
    {
        $user = $request->user('admin') ?? $request->user('sanctum') ?? $request->user();
        $reqRegion = $request->kedeputian_wilayah;
        
        $scope = $this->getUserRegionScope($user, $reqRegion);
        if ($scope) {
            $data = Province::whereIn('id', $scope['province_ids'])->orderBy('name')->get(['id', 'code', 'name']);
            return $this->successResponse('Data Provinsi (Filtered)', $data);
        }

        $provinces = Province::select('id', 'code', 'name')->orderBy('name')->get();

        // Auto-fetch if data is incomplete (Indonesia has 38 provinces)
        if ($provinces->count() < 30) {
            try {
                $response = \Illuminate\Support\Facades\Http::withOptions(['verify' => false, 'timeout' => 10])
                    ->get("https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json");
                
                if ($response->successful()) {
                    $apiData = $response->json();
                    foreach ($apiData as $d) {
                        Province::updateOrCreate(
                            ['code' => $d['id']],
                            ['name' => strtoupper($d['name'])]
                        );
                    }
                    $provinces = Province::select('id', 'code', 'name')->orderBy('name')->get();
                }
            } catch (\Exception $e) {
                // If API fails, just return what we have
            }
        }

        return $this->successResponse('Data Provinsi', $provinces);
    }

    public function cities(Request $request)
    {
        $user = $request->user('admin') ?? $request->user('sanctum') ?? $request->user();
        $provinceId = $request->province_id;
        $reqRegion = $request->kedeputian_wilayah;

        $scope = $this->getUserRegionScope($user, $reqRegion);
        if ($scope) {
            $query = City::whereIn('id', $scope['city_ids']);
            if ($provinceId) {
                $query->where('province_id', $provinceId);
            }
            $data = $query->orderBy('name')->get(['id', 'province_id', 'code', 'name', 'type']);
            return $this->successResponse('Data Kota (Filtered)', $data);
        }

        if (!$provinceId) return $this->successResponse('Data Kota', []);

        $province = Province::find($provinceId);
        if (!$province) return $this->errorResponse('Provinsi tidak ditemukan', null, 404);

        $cities = City::select('id', 'province_id', 'code', 'name', 'type')
                ->where('province_id', $provinceId)
                ->orderBy('name')
                ->get();

        // Auto-fetch cities if incomplete (A province usually has > 10 cities/regencies)
        if ($cities->count() < 10) {
            try {
                $response = \Illuminate\Support\Facades\Http::withOptions(['verify' => false, 'timeout' => 10])
                    ->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$province->code}.json");
                
                if ($response->successful()) {
                    $apiData = $response->json();
                    foreach ($apiData as $d) {
                        City::updateOrCreate(
                            ['code' => $d['id']],
                            [
                                'province_id' => $provinceId,
                                'name' => strtoupper($d['name']),
                                'type' => str_contains(strtoupper($d['name']), 'KOTA') ? 'KOTA' : 'KABUPATEN'
                            ]
                        );
                    }
                    $cities = City::select('id', 'province_id', 'code', 'name', 'type')
                        ->where('province_id', $provinceId)
                        ->orderBy('name')
                        ->get();
                }
            } catch (\Exception $e) { }
        }
        
        return $this->successResponse('Data Kota', $cities);
    }

    public function districts(Request $request)
    {
        $user = $request->user('sanctum');
        $cityId = $request->city_id;
        
        // Audit: If no cityId provided but user is a KC staff, auto-detect their city
        if (!$cityId && $user && $user->role !== 'anggota' && $user->kantor_cabang_id) {
            $cityId = $user->kantorCabang?->city_id;
        }

        if (!$cityId) return $this->successResponse('Data Kecamatan', []);

        $city = City::find($cityId);
        if (!$city) return $this->errorResponse('Kota tidak ditemukan', null, 404);

        $districts = District::where('city_id', $cityId)->orderBy('name')->get();

        // Auto-fetch if incomplete
        if ($districts->count() < 5) {
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

        $scope = $this->getUserRegionScope($user);
        
        return $this->successResponse('User Context', [
            'role' => $user->role,
            'unit_name' => $user->kantor_cabang ?? ($kc?->name ?? 'NASIONAL'),
            'scope_type' => $scope ? $scope['type'] : 'global',
            'kantor_cabang' => [
                'id' => $kc?->id,
                'name' => $kc?->name,
                'province_id' => $kc?->province_id ?? $user->province_id,
                'city_id' => $kc?->city_id ?? $user->city_id,
            ],
            'auto_fill' => (bool)(($kc?->province_id && $kc?->city_id) || ($user->province_id && $user->city_id))
        ]);
    }

    private function getUserRegionScope($user, $requestedRegion = null)
    {
        // 1. Forced Region from Parameter (Only if user has permission to see it)
        $regionName = $requestedRegion;
        
        // If not superadmin, they are locked to their own region regardless of parameter
        if ($user && $user->role !== 'superadmin' && $user->kedeputian_wilayah) {
            $regionName = $user->kedeputian_wilayah;
        }

        if ($regionName) {
            $kenwil = \App\Models\KedeputianWilayah::where('name', 'LIKE', '%' . trim($regionName) . '%')->first();
            if ($kenwil) {
                $kcs = $kenwil->kantorCabangs;
                return [
                    'type' => 'kenwil',
                    'province_ids' => $kcs->pluck('province_id')->unique()->toArray(),
                    'city_ids' => $kcs->pluck('city_id')->unique()->toArray()
                ];
            }
        }

        if (!$user || $user->role === 'superadmin' || $user->role === 'anggota') {
            return null;
        }

        // 2. Branch Scope (via ID or Name fallback)
        $kc = null;
        if ($user->kantor_cabang_id) {
            $kc = $user->kantorCabang;
        } elseif ($user->kantor_cabang) {
            $kc = \App\Models\KantorCabang::where('name', 'LIKE', '%' . trim($user->kantor_cabang) . '%')->first();
        }

        if ($kc) {
            return [
                'type' => 'branch',
                'province_ids' => [$kc->province_id],
                'city_ids' => [$kc->city_id]
            ];
        }

        return null;
    }
}
