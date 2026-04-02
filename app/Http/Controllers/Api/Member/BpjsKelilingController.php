<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpjsKeliling;
use Illuminate\Support\Facades\Auth;

class BpjsKelilingController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'jenis_kegiatan' => 'required',
            'kuadran'        => 'required',
            'judul'          => 'required|string|max:255',
            'tanggal'        => 'required|date',
            'jam_mulai'      => 'nullable',
            'jam_selesai'    => 'nullable',
            'provinsi_id'    => 'nullable|exists:provinces,id',
            'kota_id'        => 'nullable|exists:cities,id',
            'kecamatan_id'   => 'nullable|exists:districts,id',
            'nama_desa'      => 'nullable|string|max:255',
            'lokasi_detail'  => 'nullable|string',
            'lokasi_kegiatan'=> 'nullable|string',
        ]);

        // Auto-fill from User Profile (Admin/Pengurus)
        $validated['member_id'] = $user->id;
        $validated['kedeputian_wilayah'] = $user->kedeputian_wilayah ?? null;
        $validated['kantor_cabang'] = $user->kantor_cabang ?? null;
        $validated['zona_waktu'] = $user->zona_waktu ?? 'WIB';

        $item = BpjsKeliling::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Kegiatan BPJS Keliling berhasil dibuat',
            'data'    => $item
        ]);
    }

    public function addParticipant(Request $request, $id)
    {
        $kegiatan = BpjsKeliling::where('member_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'nik'               => 'required|string|digits:16',
            'name'              => 'nullable|string|max:255',
            'segmen_peserta'    => 'nullable|string',
            'phone_number'      => 'nullable|string',
            'jam_mulai'         => 'nullable',
            'jam_selesai'       => 'nullable',
            'jenis_layanan'     => 'required|string',
            'transaksi_layanan' => 'nullable|string',
            'status'            => 'required|in:Berhasil,Tidak Berhasil',
            'suara_pelanggan'   => 'required|in:Puas,Tidak puas',
            'keterangan_gagal'  => 'nullable|string',
        ]);

        $participant = $kegiatan->participants()->create($validated);
        
        // Recalculate summary in header
        $kegiatan->recalculateSummaries();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data peserta berhasil ditambahkan',
            'data'    => $participant
        ]);
    }

    public function show($id)
    {
        $item = BpjsKeliling::with(['provinsi', 'kota', 'participants'])
            ->where('member_id', Auth::id())
            ->findOrFail($id);
            
        return response()->json([
            'status' => 'success',
            'data' => $item
        ]);
    }
}
