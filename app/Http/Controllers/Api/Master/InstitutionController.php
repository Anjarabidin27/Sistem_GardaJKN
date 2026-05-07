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

    public function kedeputianWilayahs(Request $request)
    {
        $user = $request->user();
        $query = KedeputianWilayah::select('id', 'name')->orderBy('name');

        if ($user && $user->role === 'admin_wilayah' && $user->kedeputian_wilayah) {
            $query->where('name', $user->kedeputian_wilayah);
        }

        $data = $query->get();
        return $this->successResponse('Data Kedeputian Wilayah', $data);
    }

    public function kantorCabangs(Request $request)
    {
        $user = $request->user();
        $kwId = $request->kedeputian_wilayah_id;
        $query = KantorCabang::select('id', 'kedeputian_wilayah_id', 'name')->orderBy('name');
        
        if ($user && $user->role !== 'superadmin') {
            $userKW = $user->kedeputian_wilayah;
            if ($userKW) {
                $query->whereHas('kedeputianWilayah', function($q) use ($userKW) {
                    $q->where('name', $userKW);
                });
            }
        }

        if ($kwId) {
            $query->where('kedeputian_wilayah_id', $kwId);
        }

        $data = $query->get();
        return $this->successResponse('Data Kantor Cabang', $data);
    }
}
