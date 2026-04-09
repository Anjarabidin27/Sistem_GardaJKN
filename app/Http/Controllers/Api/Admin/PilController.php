<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pil;
use Illuminate\Support\Facades\Auth;

class PilController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user() ?: auth('admin')->user();

        $query = Pil::with(['provinsi', 'kota'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->dari, fn($q) => $q->whereDate('tanggal', '>=', $request->dari))
            ->when($request->sampai, fn($q) => $q->whereDate('tanggal', '<=', $request->sampai));

        // ACL (Access Control List) based on Role
        if ($user) {
            $userRole = $user->role;
            $userKC   = trim($user->kantor_cabang);
            $userKW   = trim($user->kedeputian_wilayah);

            if ($userRole === 'admin_wilayah' && $userKW) {
                $query->where('kedeputian_wilayah', 'LIKE', '%' . $userKW . '%');
            } elseif (in_array($userRole, ['petugas_pil', 'administrator', 'admin']) && $userKC) {
                // Aggressive fuzzy match: removes common prefixes like "KC "
                $cleanKC = str_ireplace('KC ', '', $userKC);
                $query->where('kantor_cabang', 'LIKE', '%' . $cleanKC . '%');
            }
        }

        $query->orderByDesc('tanggal');

        return response()->json([
            'status' => 'success',
            'data' => $query->get()->append('status_label')
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'nama_frontliner' => 'nullable|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'provinsi_id' => 'nullable|exists:provinces,id',
            'kota_id' => 'nullable|exists:cities,id',
            'kecamatan_id' => 'nullable|exists:districts,id',
            'nama_desa' => 'nullable|string|max:255',
            'lokasi_kegiatan' => 'nullable|string|max:255',
            'lokasi_detail' => 'nullable|string',
            'jumlah_petugas' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled'
        ]);

        $user = auth()->user() ?: auth('admin')->user();
        if ($user) {
            $validated['created_by'] = $user->id;
            $validated['kedeputian_wilayah'] = $user->kedeputian_wilayah;
            $validated['kantor_cabang'] = $user->kantor_cabang;
            $validated['zona_waktu'] = $user->zona_waktu ?: 'WIB';
        } else {
            $validated['created_by'] = 1;
        }

        $item = Pil::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kegiatan PIL berhasil ditambahkan',
            'data' => $item
        ]);
    }

    public function show($id)
    {
        $item = Pil::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $item
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = Pil::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'nama_frontliner' => 'nullable|string',
            'tanggal' => 'required|date',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'provinsi_id' => 'nullable|exists:provinces,id',
            'kota_id' => 'nullable|exists:cities,id',
            'kecamatan_id' => 'nullable|exists:districts,id',
            'nama_desa' => 'nullable|string|max:255',
            'lokasi_kegiatan' => 'nullable|string|max:255',
            'lokasi_detail' => 'nullable|string',
            'jumlah_petugas' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled'
        ]);

        $item->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kegiatan PIL berhasil diupdate',
            'data' => $item
        ]);
    }

    public function destroy($id)
    {
        $item = Pil::findOrFail($id);
        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kegiatan PIL berhasil dihapus'
        ]);
    }

    // --- Master Detail Laporan Peserta ---

    public function getParticipants($id)
    {
        $kegiatan = Pil::with('participants')->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data'   => $kegiatan->participants()->orderByDesc('created_at')->get()
        ]);
    }

    public function storeParticipant(Request $request, $id)
    {
        $kegiatan = Pil::findOrFail($id);

        if ($kegiatan->status === 'cancelled') {
            return response()->json(['status' => 'error', 'message' => 'Kegiatan telah dibatalkan.'], 422);
        }

        $validated = $request->validate([
            'nik'                     => 'required|string|max:16',
            'phone_number'            => 'nullable|string',
            'segmen_peserta'          => 'required|string',
            'jam_sosialisasi_mulai'   => 'nullable',
            'jam_sosialisasi_selesai' => 'nullable',
            'nilai_pemahaman'         => 'required|integer|min:0|max:100',
            'efektifitas_sosialisasi' => 'required|string',
            'nps_ketertarikan'        => 'required|integer|min:1|max:10',
            'nps_rekomendasi_program' => 'required|integer|min:1|max:10',
            'nps_rekomendasi_bpjs'    => 'required|integer|min:1|max:10',
        ]);

        if ($kegiatan->status === 'scheduled') {
            $kegiatan->update(['status' => 'ongoing']);
        }

        $participant = $kegiatan->participants()->create($validated);
        $kegiatan->recalculateSummaries();

        return response()->json([
            'status'  => 'success',
            'message' => 'Peserta berhasil disimpan!',
            'data'    => $participant
        ]);
    }

    public function finishLaporan($id)
    {
        $kegiatan = Pil::findOrFail($id);
        $kegiatan->update(['status' => 'completed']);
        return response()->json([
            'status' => 'success',
            'message' => 'Laporan selesai, kegiatan ditutup.'
        ]);
    }

    public function destroyParticipant($id, $participant_id)
    {
        $kegiatan = Pil::findOrFail($id);
        $p = $kegiatan->participants()->findOrFail($participant_id);
        $p->delete();
        $kegiatan->recalculateSummaries();

        return response()->json([
            'status' => 'success',
            'message' => 'Data peserta dihapus'
        ]);
    }

    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $query = Pil::query()
            ->when($request->dari, fn($q) => $q->whereDate('tanggal', '>=', $request->dari))
            ->when($request->sampai, fn($q) => $q->whereDate('tanggal', '<=', $request->sampai))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->provinsi_id, fn($q) => $q->where('provinsi_id', $request->provinsi_id))
            ->when($request->kota_id, fn($q) => $q->where('kota_id', $request->kota_id));

        // ACL Filtering (Mandatory for security & accuracy)
        if ($user->role === 'superadmin') {
            if ($request->kedeputian_wilayah) {
                $query->where('kedeputian_wilayah', 'LIKE', '%' . $request->kedeputian_wilayah . '%');
            }
            if ($request->kantor_cabang) {
                $query->where('kantor_cabang', 'LIKE', '%' . $request->kantor_cabang . '%');
            }
        } elseif ($user->role === 'admin' || $user->role === 'administrator' || $user->role === 'admin_wilayah') {
            // Priority 1: User's owned regional context
            if ($user->kedeputian_wilayah) {
                $query->where('kedeputian_wilayah', 'LIKE', '%' . $user->kedeputian_wilayah . '%');
            }
            
            // Priority 2: Manual filters within allowed context
            if ($request->kantor_cabang) {
                $query->where('kantor_cabang', 'LIKE', '%' . $request->kantor_cabang . '%');
            } elseif ($user->kantor_cabang) {
                $query->where('kantor_cabang', 'LIKE', '%' . $user->kantor_cabang . '%');
            }
        }

        $kegiatan = $query->get();

        // Prepare context strings for UI
        $contextLabel = 'Nasional';
        if ($user->role === 'superadmin') {
            $contextLabel = $request->kantor_cabang ?: ($request->kedeputian_wilayah ?: 'Nasional');
        } else {
            $contextLabel = $user->kantor_cabang ?: ($user->kedeputian_wilayah ?: 'Nasional');
        }

        // Additional Stats for Excel Compliance
        $totalPeserta = $kegiatan->sum('jumlah_peserta');
        $uniqueDesaCount = $kegiatan->whereNotNull('nama_desa')->unique('nama_desa')->count();
        
        // Target Kepesertaan JKN (Simulated target/master data)
        // In real app, this should come from a Setting or Target table
        $targetJKN = 25000; 
        $targetDesa = 500; 

        return response()->json([
            'status' => 'success',
            'data' => [
                'context' => $contextLabel,
                'total_kegiatan'          => $kegiatan->count(),
                'total_peserta'           => (int)$totalPeserta,
                'rata_pemahaman'          => (float)$kegiatan->avg('rata_pemahaman') ?: 0,
                
                // Excel Specs: Capaian Coverage Sosialisasi
                'target_jkn'              => $targetJKN,
                'coverage_sosialisasi'    => $targetJKN > 0 ? ($totalPeserta / $targetJKN) * 100 : 0,

                // Excel Specs: Capaian Desa
                'total_desa_terjamah'     => $uniqueDesaCount,
                'target_desa'             => $targetDesa,
                'persentase_desa'         => $targetDesa > 0 ? ($uniqueDesaCount / $targetDesa) * 100 : 0,
                
                // Efektifitas Sosialisasi
                'count_sangat_efektif'    => $kegiatan->sum('count_sangat_efektif'),
                'count_efektif'           => $kegiatan->sum('count_efektif'),
                'count_kurang_efektif'    => $kegiatan->sum('count_kurang_efektif'),
                
                // Segmen Peserta Breakdown
                'segmen_breakdown' => [
                    'PBPU' => $kegiatan->sum('count_seg_pbpu'),
                    'BP' => $kegiatan->sum('count_seg_bp'),
                    'PPU BU' => $kegiatan->sum('count_seg_ppu_bu'),
                    'PPU Pemerintah' => $kegiatan->sum('count_seg_ppu_pem'),
                    'PBI APBN' => $kegiatan->sum('count_seg_pbi_apbn'),
                    'PBI APBD' => $kegiatan->sum('count_seg_pbi_apbd'),
                ],

                // Lokasi Kegiatan Breakdown
                'lokasi_breakdown' => $kegiatan->groupBy('lokasi_kegiatan')->map->count(),

                // NPS averages
                'rata_nps_ketertarikan'         => (float)$kegiatan->avg('rata_nps_ketertarikan') ?: 0,
                'rata_nps_rekomendasi_program'  => (float)$kegiatan->avg('rata_nps_rekomendasi_program') ?: 0,
                'rata_nps_rekomendasi_bpjs'     => (float)$kegiatan->avg('rata_nps_rekomendasi_bpjs') ?: 0,
            ]
        ]);
    }
}
