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
            'jenis_kegiatan' => 'required|string',
            'kuadran' => 'nullable|string',
            'nama_frontliner' => 'nullable|string',
            'judul' => 'required|string|max:255',
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
            $validated['created_by'] = 1; // Fallback
        }

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
            'jenis_kegiatan' => 'required|string',
            'kuadran' => 'nullable|string',
            'nama_frontliner' => 'nullable|string',
            'judul' => 'required|string|max:255',
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

    // --- Master-Detail Laporan (Entry Peserta) ---

    public function getParticipants($id)
    {
        $kegiatan = BpjsKeliling::with('participants')->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data'   => $kegiatan->participants()->orderByDesc('created_at')->get()
        ]);
    }

    public function storeParticipant(Request $request, $id)
    {
        $kegiatan = BpjsKeliling::findOrFail($id);

        if ($kegiatan->status === 'cancelled') {
            return response()->json(['status' => 'error', 'message' => 'Kegiatan telah dibatalkan.'], 422);
        }

        $validated = $request->validate([
            'nik'               => 'required|string|max:16',
            'segmen_peserta'    => 'required|string',
            'phone_number'      => 'nullable|string',
            'jam_mulai'         => 'nullable',
            'jam_selesai'       => 'nullable',
            'jenis_layanan'     => 'required|in:Administrasi,Informasi,Pengaduan',
            'transaksi_layanan' => 'nullable|string',
            'status'            => 'required|in:Berhasil,Tidak Berhasil',
            'keterangan_gagal'  => 'nullable|string',
            'suara_pelanggan'   => 'nullable|in:Puas,Tidak puas'
        ]);

        // Otomatis ubah header status menjadi completed jika mulai ngisi peserta (sesuai kemudahan) atau biarkan ongoing.
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
        $kegiatan = BpjsKeliling::findOrFail($id);
        $kegiatan->update(['status' => 'completed']);
        return response()->json([
            'status' => 'success',
            'message' => 'Laporan selesai, kegiatan ditutup.'
        ]);
    }

    public function destroyParticipant($id, $participant_id)
    {
        $kegiatan = BpjsKeliling::findOrFail($id);
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
