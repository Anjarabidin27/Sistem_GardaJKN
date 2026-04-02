<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pil;
use Illuminate\Support\Facades\Auth;

class PilController extends Controller
{
    public function index(Request $request)
    {
        $data = Pil::with(['provinsi', 'kota', 'participants'])
            ->where('member_id', Auth::id())
            ->orderByDesc('tanggal')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'tanggal'        => 'required|date',
            'jam_mulai'      => 'nullable',
            'jam_selesai'    => 'nullable',
            'provinsi_id'    => 'nullable|exists:provinces,id',
            'kota_id'        => 'nullable|exists:cities,id',
            'kecamatan_id'   => 'nullable|exists:districts,id',
            'nama_desa'      => 'nullable|string|max:255',
            'lokasi_kegiatan'=> 'nullable|string',
            'nama_frontliner'=> 'required|string',
        ]);

        $validated['member_id'] = $user->id;
        $validated['kedeputian_wilayah'] = $user->kedeputian_wilayah ?? null;
        $validated['kantor_cabang'] = $user->kantor_cabang ?? null;
        $validated['zona_waktu'] = $user->zona_waktu ?? 'WIB';

        $item = Pil::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Laporan PIL berhasil dimulai',
            'data'    => $item
        ]);
    }

    public function addParticipant(Request $request, $id)
    {
        $kegiatan = Pil::where('member_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'nik'               => 'required|string|digits:16',
            'name'              => 'nullable|string|max:255',
            'segmen_peserta'    => 'nullable|string',
            'phone_number'      => 'nullable|string',
            'jam_sosialisasi_mulai'   => 'nullable',
            'jam_sosialisasi_selesai' => 'nullable',
            'nilai_pemahaman'         => 'required|integer|min:0|max:100',
            'efektifitas_sosialisasi' => 'required|string',
            'nps_ketertarikan'        => 'required|integer|min:1|max:10',
            'nps_rekomendasi_program' => 'required|integer|min:1|max:10',
            'nps_rekomendasi_bpjs'    => 'required|integer|min:1|max:10',
        ]);

        $participant = $kegiatan->participants()->create($validated);
        
        $kegiatan->recalculateSummaries();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data peserta PIL berhasil ditambahkan',
            'data'    => $participant
        ]);
    }

    public function show($id)
    {
        $item = Pil::with(['provinsi', 'kota', 'participants'])
            ->where('member_id', Auth::id())
            ->findOrFail($id);
            
        return response()->json([
            'status' => 'success',
            'data' => $item
        ]);
    }
}
