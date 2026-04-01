<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpjsKeliling;

class BpjsKelilingController extends Controller
{
    public function index(Request $request)
    {
        $data = BpjsKeliling::with(['provinsi', 'kota'])
            ->whereIn('status', ['scheduled', 'ongoing', 'completed'])
            ->orderByDesc('tanggal')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $item = BpjsKeliling::with(['provinsi', 'kota'])
            ->whereIn('status', ['scheduled', 'ongoing', 'completed'])
            ->findOrFail($id);
            
        return response()->json([
            'status' => 'success',
            'data' => $item
        ]);
    }
}
