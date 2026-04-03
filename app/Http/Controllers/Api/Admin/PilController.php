<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PilKegiatan;
use Illuminate\Support\Facades\Auth;

class PilController extends Controller
{
    public function index(Request $request)
    {
        $query = PilKegiatan::with(['provinsi', 'kota'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->dari, fn($q) => $q->whereDate('tanggal', '>=', $request->dari))
            ->when($request->sampai, fn($q) => $q->whereDate('tanggal', '<=', $request->sampai))
            ->orderByDesc('tanggal');

        return response()->json([
            'status' => 'success',
            'data' => $query->get()
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

        $user = Auth::guard('admin')->user();
        if ($user) {
            $validated['created_by'] = $user->id;
            $validated['kedeputian_wilayah'] = $user->kedeputian_wilayah;
            $validated['kantor_cabang'] = $user->kantor_cabang;
            $validated['zona_waktu'] = $user->zona_waktu ?: 'WIB';
        } else {
            $validated['created_by'] = 1;
        }

        $item = PilKegiatan::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kegiatan PIL berhasil ditambahkan',
            'data' => $item
        ]);
    }

    public function show($id)
    {
        $item = PilKegiatan::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $item
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = PilKegiatan::findOrFail($id);

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
        $item = PilKegiatan::findOrFail($id);
        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kegiatan PIL berhasil dihapus'
        ]);
    }

    // --- Master Detail Laporan Peserta ---

    public function getParticipants($id)
    {
        $kegiatan = PilKegiatan::with('participants')->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data'   => $kegiatan->participants()->orderByDesc('created_at')->get()
        ]);
    }

    public function storeParticipant(Request $request, $id)
    {
        $kegiatan = PilKegiatan::findOrFail($id);

        if ($kegiatan->status === 'cancelled') {
            return response()->json(['status' => 'error', 'message' => 'Kegiatan telah dibatalkan.'], 422);
        }

        $validated = $request->validate([
            'nik'                     => 'required|string|max:16',
            'segmen_peserta'          => 'required|string',
            'phone_number'            => 'nullable|string',
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
        $kegiatan = PilKegiatan::findOrFail($id);
        $kegiatan->update(['status' => 'completed']);
        return response()->json([
            'status' => 'success',
            'message' => 'Laporan selesai, kegiatan ditutup.'
        ]);
    }

    public function destroyParticipant($id, $participant_id)
    {
        $kegiatan = PilKegiatan::findOrFail($id);
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
        $query = PilKegiatan::query()
            ->when($request->dari, fn($q) => $q->whereDate('tanggal', '>=', $request->dari))
            ->when($request->sampai, fn($q) => $q->whereDate('tanggal', '<=', $request->sampai));

        $kegiatan = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_kegiatan'          => $kegiatan->count(),
                'total_peserta'           => $kegiatan->sum('jumlah_peserta'),
                'rata_nps_ketertarikan'   => (float)$kegiatan->avg('rata_nps_ketertarikan') ?: 0,
                'rata_nps_rekomendasi_program' => (float)$kegiatan->avg('rata_nps_rekomendasi_program') ?: 0,
                'rata_nps_rekomendasi_bpjs' => (float)$kegiatan->avg('rata_nps_rekomendasi_bpjs') ?: 0,
            ]
        ]);
    }
}
