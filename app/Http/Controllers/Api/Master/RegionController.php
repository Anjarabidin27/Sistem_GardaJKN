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

        $data = \Illuminate\Support\Facades\Cache::rememberForever("districts_city_{$cityId}", function() use ($cityId) {
            return District::select('id', 'city_id', 'code', 'name')
                ->where('city_id', $cityId)
                ->orderBy('name')
                ->get();
        });

        return $this->successResponse('Data Kecamatan', $data);
    }
}
