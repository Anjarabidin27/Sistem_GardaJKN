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
            'tanggal' => 'required|date',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'provinsi_id' => 'nullable|exists:provinces,id',
            'kota_id' => 'nullable|exists:cities,id',
            'kecamatan_id' => 'nullable|exists:districts,id',
            'nama_desa' => 'nullable|string|max:255',
            'lokasi_detail' => 'nullable|string',
            'jumlah_petugas' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled'
        ]);

        $validated['created_by'] = Auth::guard('admin')->id() ?? 1;

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
            'tanggal' => 'required|date',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'provinsi_id' => 'nullable|exists:provinces,id',
            'kota_id' => 'nullable|exists:cities,id',
            'kecamatan_id' => 'nullable|exists:districts,id',
            'nama_desa' => 'nullable|string|max:255',
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

    public function storeLaporan(Request $request, $id)
    {
        $kegiatan = PilKegiatan::findOrFail($id);

        if ($kegiatan->status === 'cancelled') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak dapat mengisi laporan untuk kegiatan yang dibatalkan.',
            ], 422);
        }

        $validated = $request->validate([
            'jumlah_peserta'        => 'required|integer|min:0',
            'rata_uji_pemahaman'    => 'required|numeric|min:0|max:100',
            'efek_ketertarikan_jkn' => 'required|integer|min:1|max:10',
            'efek_rekomendasi_jkn'  => 'required|integer|min:1|max:10',
            'efek_rekomendasi_bpjs' => 'required|integer|min:1|max:10',
            'catatan'               => 'nullable|string',
        ]);

        // Otomatis set status menjadi 'completed' setelah laporan diisi
        $validated['status'] = 'completed';

        $kegiatan->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Laporan PIL berhasil disimpan',
            'data'    => $kegiatan
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
                'rata_pemahaman'          => (float)$kegiatan->avg('rata_uji_pemahaman') ?: 0,
                'rata_efek_ketertarikan'  => (float)$kegiatan->avg('efek_ketertarikan_jkn') ?: 0,
                'rata_efek_rekomendasi_jkn' => (float)$kegiatan->avg('efek_rekomendasi_jkn') ?: 0,
                'rata_efek_rekomendasi_bpjs' => (float)$kegiatan->avg('efek_rekomendasi_bpjs') ?: 0,
            ]
        ]);
    }
}
