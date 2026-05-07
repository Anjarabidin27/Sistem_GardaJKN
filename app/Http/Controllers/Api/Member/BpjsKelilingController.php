<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpjsKeliling;
use Illuminate\Support\Facades\Auth;

class BpjsKelilingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = BpjsKeliling::with(['provinsi', 'kota', 'participants'])
            ->orderByDesc('tanggal');

        // If Pengurus, filter by their branch
        if ($user && $user->role === 'pengurus') {
            $user->load('kantorCabang');
            if ($user->kantorCabang) {
                $cleanKC = str_ireplace('KC ', '', $user->kantorCabang->name);
                $query->where('kantor_cabang', 'LIKE', '%' . $cleanKC . '%');
            } else {
                // If no branch assigned, only see their own records
                $query->where('member_id', $user->id);
            }
        } else if ($user) {
            // Regular member only sees their own
            $query->where('member_id', $user->id);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $query->get()->append('status_label')
        ]);
    }
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
        
        if ($user->role === 'pengurus') {
            $user->load(['kantorCabang.kedeputianWilayah']);
            $validated['kantor_cabang'] = $user->kantorCabang?->name;
            $validated['kedeputian_wilayah'] = $user->kantorCabang?->kedeputianWilayah?->name;
        } else {
            $validated['kedeputian_wilayah'] = $user->kedeputian_wilayah ?? null;
            $validated['kantor_cabang'] = $user->kantor_cabang ?? null;
        }

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
        $user = $request->user();
        $query = BpjsKeliling::query();

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
            'jam_mulai'         => 'nullable',
            'jam_selesai'       => 'nullable',
            'jenis_layanan'     => 'required|string',
            'transaksi_layanan' => 'nullable|string',
            'status'            => 'required|in:Berhasil,Tidak Berhasil',
            'suara_pelanggan'   => 'required|in:Puas,Tidak puas',
            'keterangan_gagal'  => 'nullable|string',
        ]);

        $exists = \App\Models\BpjsKelilingParticipant::whereHas('activity', function($q) use ($kegiatan) {
            $q->whereDate('tanggal', $kegiatan->tanggal);
        })->where(function($q) use ($request) {
            $q->where('nik', $request->nik);
            if ($request->phone_number) {
                $q->orWhere('phone_number', $request->phone_number);
            }
        })->exists();

        if ($exists) {
            return response()->json([
                'status'  => 'error',
                'message' => 'NIK atau Nomor HP sudah terdaftar pada kegiatan di hari yang sama.'
            ], 422);
        }

        $participant = $kegiatan->participants()->create($validated);
        
        // Recalculate summary in header
        $kegiatan->recalculateSummaries();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data peserta berhasil ditambahkan',
            'data'    => $participant
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $query = BpjsKeliling::with(['provinsi', 'kota', 'participants']);

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
