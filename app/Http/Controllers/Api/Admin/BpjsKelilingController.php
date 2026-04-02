<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BpjsKeliling;
use Illuminate\Support\Facades\Auth;

class BpjsKelilingController extends Controller
{
    public function index(Request $request)
    {
        $query = BpjsKeliling::with(['provinsi', 'kota'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->jenis, fn($q) => $q->where('jenis_kegiatan', $request->jenis))
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
            'jenis_kegiatan' => 'required|in:goes_to_village,around_city,goes_to_office,institusi,pameran,other',
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

        $item = BpjsKeliling::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $item
        ]);
    }

    public function show($id)
    {
        $item = BpjsKeliling::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $item
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = BpjsKeliling::findOrFail($id);

        $validated = $request->validate([
            'jenis_kegiatan' => 'required|in:goes_to_village,around_city,goes_to_office,institusi,pameran,other',
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
            'message' => 'Jadwal berhasil diupdate',
            'data' => $item
        ]);
    }

    public function destroy($id)
    {
        $item = BpjsKeliling::findOrFail($id);
        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal berhasil dihapus'
        ]);
    }

    public function storeLaporan(Request $request, $id)
    {
        $kegiatan = BpjsKeliling::findOrFail($id);

        if ($kegiatan->status === 'cancelled') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak dapat mengisi laporan untuk kegiatan yang dibatalkan.',
            ], 422);
        }

        $validated = $request->validate([
            'layanan_informasi'    => 'required|integer|min:0',
            'layanan_administrasi' => 'required|integer|min:0',
            'layanan_pengaduan'    => 'required|integer|min:0',
            'transaksi_berhasil'   => 'required|integer|min:0',
            'transaksi_gagal'      => 'required|integer|min:0',
            'jumlah_peserta'       => 'required|integer|min:0',
            'kepuasan_puas'        => 'required|integer|min:0',
            'kepuasan_tidak_puas'  => 'required|integer|min:0',
            'catatan'              => 'nullable|string',
        ]);

        // Otomatis set status menjadi 'completed' setelah laporan diisi
        $validated['status'] = 'completed';

        $kegiatan->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Laporan berhasil disimpan',
            'data'    => $kegiatan
        ]);
    }

    public function dashboard(Request $request)
    {
        $query = BpjsKeliling::query()
            ->when($request->dari, fn($q) => $q->whereDate('tanggal', '>=', $request->dari))
            ->when($request->sampai, fn($q) => $q->whereDate('tanggal', '<=', $request->sampai));

        $kegiatan = $query->get();

        $totalPuas      = $kegiatan->sum('kepuasan_puas');
        $totalTidakPuas = $kegiatan->sum('kepuasan_tidak_puas');
        $totalResponden = $totalPuas + $totalTidakPuas;

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_kegiatan'           => $kegiatan->count(),
                'total_peserta'            => $kegiatan->sum('jumlah_peserta'),
                'total_informasi'          => $kegiatan->sum('layanan_informasi'),
                'total_administrasi'       => $kegiatan->sum('layanan_administrasi'),
                'total_pengaduan'          => $kegiatan->sum('layanan_pengaduan'),
                'total_transaksi_berhasil' => $kegiatan->sum('transaksi_berhasil'),
                'total_transaksi_gagal'    => $kegiatan->sum('transaksi_gagal'),
                'rata_kepuasan_persen'     => $totalResponden > 0
                    ? round(($totalPuas / $totalResponden) * 100, 2)
                    : 0,
                'per_jenis_kegiatan'       => $kegiatan->groupBy('jenis_kegiatan')->map(fn($g) => [
                    'label'           => \App\Models\BpjsKeliling::JENIS_KEGIATAN[$g->first()->jenis_kegiatan] ?? $g->first()->jenis_kegiatan,
                    'jumlah_kegiatan' => $g->count(),
                    'total_peserta'   => $g->sum('jumlah_peserta'),
                ]),
            ]
        ]);
    }
}
