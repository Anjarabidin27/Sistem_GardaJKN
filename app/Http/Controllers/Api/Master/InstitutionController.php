<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\KantorCabang;
use App\Models\KedeputianWilayah;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    use ApiResponse;

    public function kedeputianWilayahs()
    {
        $data = KedeputianWilayah::select('id', 'name')->orderBy('name')->get();
        return $this->successResponse('Data Kedeputian Wilayah', $data);
    }

    public function kantorCabangs(Request $request)
    {
        $kwId = $request->kedeputian_wilayah_id;
        $query = KantorCabang::select('id', 'kedeputian_wilayah_id', 'name')->orderBy('name');
        
        if ($kwId) {
            $query->where('kedeputian_wilayah_id', $kwId);
        }

        $data = $query->get();
        return $this->successResponse('Data Kantor Cabang', $data);
    }
}
