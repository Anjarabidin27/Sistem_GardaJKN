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
        
        // Only apply filter if the scope actually yielded valid province IDs
        // This prevents the dropdown from breaking if the database is missing province_id mappings
        $validProvIds = $scope ? array_filter($scope['province_ids']) : [];
        
        if ($scope && !empty($validProvIds)) {
            $data = Province::whereIn('id', $validProvIds)->orderBy('name')->get(['id', 'code', 'name']);
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
            $query = City::query();
            
            // If they are Admin Cabang, lock to their specific cities
            if ($scope['type'] === 'branch' && !empty($scope['city_ids'])) {
                $query->whereIn('id', $scope['city_ids']);
            }
            
            // For both Admin Wilayah and Admin Cabang, lock to their specific provinces
            if (!empty($scope['province_ids'])) {
                $query->whereIn('province_id', $scope['province_ids']);
            }
            
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
                'province_id' => $kc?->province_id ?? ($user instanceof \App\Models\Member ? $user->province_id : null),
                'city_id' => $kc?->city_id ?? ($user instanceof \App\Models\Member ? $user->city_id : null),
            ],
            'auto_fill' => (bool)(($kc?->province_id && $kc?->city_id) || ($user instanceof \App\Models\Member && $user->province_id && $user->city_id))
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
            // First try to get Kenwil from the User's KC relationship (most accurate)
            $kenwil = null;
            if ($user && $user->kantor_cabang_id && $user->kantorCabang) {
                $kenwil = $user->kantorCabang->kedeputianWilayah;
            }
            
            // Fallback to string matching
            if (!$kenwil) {
                $kenwil = \App\Models\KedeputianWilayah::where('name', 'LIKE', '%' . trim($regionName) . '%')->first();
            }

            if ($kenwil) {
                $kcs = $kenwil->kantorCabangs;
                $cityIds = array_values(array_filter($kcs->pluck('city_id')->unique()->toArray()));
                $provIds = array_values(array_filter($kcs->pluck('province_id')->unique()->toArray()));
                
                // If province_ids are null (old data), derive from City records based on city_ids
                if (empty($provIds) && !empty($cityIds)) {
                    $provIds = City::whereIn('id', $cityIds)->distinct()->pluck('province_id')->filter()->values()->toArray();
                }
                
                return [
                    'type' => 'kenwil',
                    'province_ids' => $provIds,
                    'city_ids' => $cityIds
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
            $kcProvinceId = $kc->province_id;
            // Derive province_id from City if missing (old data)
            if (!$kcProvinceId && $kc->city_id) {
                $kcProvinceId = City::find($kc->city_id)?->province_id;
            }
            return [
                'type' => 'branch',
                'province_ids' => array_filter([$kcProvinceId]),
                'city_ids' => array_filter([$kc->city_id])
            ];
        }

        return null;
    }
}
