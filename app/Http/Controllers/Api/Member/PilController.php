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
        $user = $request->user();
        $query = Pil::with(['provinsi', 'kota', 'participants'])
            ->orderByDesc('tanggal');

        // If Pengurus, filter by their branch
        if ($user && $user->role === 'pengurus') {
            $user->load('kantorCabang');
            if ($user->kantorCabang) {
                $cleanKC = str_ireplace('KC ', '', $user->kantorCabang->name);
                $query->where('kantor_cabang', 'LIKE', '%' . $cleanKC . '%');
            } else {
                $query->where('member_id', $user->id);
            }
        } else if ($user) {
            $query->where('member_id', $user->id);
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get()
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

        // Auto-fill from User Profile (Admin/Pengurus)
        $validated['member_id'] = $user->id;
        
        if ($user->role === 'pengurus') {
            $user->load(['kantorCabang.kedeputianWilayah']);
            $validated['kantor_cabang'] = $user->kantorCabang?->name;
            $validated['kedeputian_wilayah'] = $user->kantorCabang?->kedeputianWilayah?->name;
        } else {
            $validated['kedeputian_wilayah'] = $user->kedeputian_wilayah ?? null;
            $validated['kantor_cabang'] = $user->kantor_cabang ?? null;
        }

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
        $user = $request->user();
        $query = Pil::query();

        if ($user->role === 'pengurus') {
            $user->load('kantorCabang');
            if ($user->kantorCabang) {
                $cleanKC = str_ireplace('KC ', '', $user->kantorCabang->name);
                $query->where('kantor_cabang', 'LIKE', '%' . $cleanKC . '%');
            } else {
                $query->where('member_id', $user->id);
            }
        } else {
            $query->where('member_id', $user->id);
        }

        $kegiatan = $query->findOrFail($id);

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

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $query = Pil::with(['provinsi', 'kota', 'participants']);

        if ($user->role === 'pengurus') {
            $user->load('kantorCabang');
            if ($user->kantorCabang) {
                $cleanKC = str_ireplace('KC ', '', $user->kantorCabang->name);
                $query->where('kantor_cabang', 'LIKE', '%' . $cleanKC . '%');
            } else {
                $query->where('member_id', $user->id);
            }
        } else {
            $query->where('member_id', $user->id);
        }

        $item = $query->findOrFail($id);
            
        return response()->json([
            'status' => 'success',
            'data' => $item
        ]);
    }
}
