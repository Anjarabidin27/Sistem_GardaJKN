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
        $user = auth()->user() ?: auth('admin')->user();

        $query = BpjsKeliling::with(['provinsi', 'kota'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->jenis, fn($q) => $q->where('jenis_kegiatan', $request->jenis))
            ->when($request->dari, fn($q) => $q->whereDate('tanggal', '>=', $request->dari))
            ->when($request->sampai, fn($q) => $q->whereDate('tanggal', '<=', $request->sampai));

        // ACL (Access Control List) based on Role
        if ($user) {
            $userRole = $user->role;
            $userKC   = trim($user->kantor_cabang);
            $userKW   = trim($user->kedeputian_wilayah);

            if ($userRole === 'admin_wilayah' && $userKW) {
                $query->where('kedeputian_wilayah', 'LIKE', '%' . $userKW . '%');
            } elseif (in_array($userRole, ['petugas_keliling', 'petugas_pil', 'administrator', 'admin']) && $userKC) {
                // Robust fuzzy match: removes common prefixes like "KC "
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

        $user = auth()->user() ?: auth('admin')->user();
        if ($user) {
            $validated['created_by'] = $user->id;
            
            // Automatically fill from user's institutional context if not provided
            if (empty($validated['kedeputian_wilayah'])) {
                $validated['kedeputian_wilayah'] = $user->kedeputian_wilayah;
            }
            if (empty($validated['kantor_cabang'])) {
                $validated['kantor_cabang'] = $user->kantor_cabang;
            }
            
            $validated['zona_waktu'] = $user->zona_waktu ?: 'WIB';
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
        // Use generic auth() which should point to the correct guard via sanctum or session
        $user = auth()->user() ?: auth('admin')->user();
        
        $query = BpjsKeliling::query()
            ->when($request->dari, fn($q) => $q->whereDate('tanggal', '>=', $request->dari))
            ->when($request->sampai, fn($q) => $q->whereDate('tanggal', '<=', $request->sampai))
            ->when($request->jenis_kegiatan, fn($q) => $q->where('jenis_kegiatan', $request->jenis_kegiatan))
            ->when($request->kuadran, fn($q) => $q->where('kuadran', $request->kuadran))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->provinsi_id, fn($q) => $q->where('provinsi_id', $request->provinsi_id))
            ->when($request->kota_id, fn($q) => $q->where('kota_id', $request->kota_id));

        // ACL (Access Control List) based on Role - Only if user exists
        $contextLabel = 'Nasional';

        if ($user) {
            $userRole = $user->role;
            $userKC   = trim($user->kantor_cabang);
            $userKW   = trim($user->kedeputian_wilayah);

            if ($userRole === 'superadmin') {
                $contextLabel = 'Nasional';
                // Jika Superadmin memilih filter manual, filter tersebut akan menimpa filter regional default
                if ($request->kedeputian_wilayah) {
                    $query->where('kedeputian_wilayah', 'LIKE', '%' . $request->kedeputian_wilayah . '%');
                }
                if ($request->kantor_cabang) {
                    $query->where('kantor_cabang', 'LIKE', '%' . $request->kantor_cabang . '%');
                }
            } elseif ($userRole === 'admin_wilayah' && $userKW) {
                $contextLabel = $userKW;
                $query->where('kedeputian_wilayah', 'LIKE', '%' . $userKW . '%');
                // Admin Wilayah bisa memfilter level KC di bawahnya
                if ($request->kantor_cabang) {
                    $query->where('kantor_cabang', 'LIKE', '%' . $request->kantor_cabang . '%');
                }
            } elseif (in_array($userRole, ['petugas_keliling', 'administrator', 'admin']) && $userKC) {
                $contextLabel = $userKC;
                $cleanKC = str_ireplace('KC ', '', $userKC);
                $query->where('kantor_cabang', 'LIKE', '%' . $cleanKC . '%');
            }
        }

        $kegiatan = $query->with('participants')->get();
        $allParticipants = $kegiatan->flatMap(function($k) {
            return $k->participants;
        });

        $totalHadir = $allParticipants->count();
        $totalInfo = $allParticipants->where('jenis_layanan', 'Informasi')->count();
        $totalAdmin = $allParticipants->where('jenis_layanan', 'Administrasi')->count();
        $totalAduan = $allParticipants->where('jenis_layanan', 'Pengaduan')->count();

        $successCount = $allParticipants->where('status', 'Berhasil')->count();
        $failedCount = $allParticipants->where('status', 'Tidak Berhasil')->count();

        // Items for Dashboard (matching the image specs)
        return response()->json([
            'status' => 'success',
            'data' => [
                'context' => $contextLabel,
                // 1, 2, 3, 4: Basic Stats & Percentages
                'total_peserta' => $totalHadir,
                'layanan_informasi' => [
                    'count' => $totalInfo,
                    'pct'   => $totalHadir > 0 ? round(($totalInfo / $totalHadir) * 100, 1) : 0
                ],
                'layanan_administrasi' => [
                    'count' => $totalAdmin,
                    'pct'   => $totalHadir > 0 ? round(($totalAdmin / $totalHadir) * 100, 1) : 0
                ],
                'layanan_pengaduan' => [
                    'count' => $totalAduan,
                    'pct'   => $totalHadir > 0 ? round(($totalAduan / $totalHadir) * 100, 1) : 0
                ],

                // 5: Transaction Status
                'status_transaksi' => [
                    'berhasil' => $successCount,
                    'gagal'    => $failedCount,
                    'pct_berhasil' => $totalHadir > 0 ? round(($successCount / $totalHadir) * 100, 1) : 0
                ],

                // 6: SUPEL (Satisfaction)
                'avg_supel' => $allParticipants->whereNotNull('suara_pelanggan')->count() > 0
                    ? round(($allParticipants->where('suara_pelanggan', 'Puas')->count() / $allParticipants->whereNotNull('suara_pelanggan')->count()) * 100, 1)
                    : 0,

                // 7: Segmen Peserta Breakdown
                'per_segmen' => $allParticipants->groupBy('segmen_peserta')->map(fn($g) => $g->count()),

                // 8: Capaian Desa (Unique Villages reached)
                'capaian_desa' => [
                    'count'    => $kegiatan->pluck('nama_desa')->unique()->filter()->count(),
                    'total_kegiatan' => $kegiatan->count()
                ],

                // 9: Transaksi Layanan (Khusus Administrasi)
                'transaksi_administrasi_breakdown' => $allParticipants->where('jenis_layanan', 'Administrasi')
                    ->whereNotNull('transaksi_layanan')
                    ->groupBy('transaksi_layanan')->map(fn($g) => $g->count()),

                // 10: Kuadran
                'per_kuadran' => $kegiatan->groupBy('kuadran')->map(fn($g) => $g->count()),

                // 11: Jenis Kegiatan
                'per_jenis_kegiatan' => $kegiatan->groupBy('jenis_kegiatan')->map(fn($g) => [
                    'label' => \App\Models\BpjsKeliling::JENIS_KEGIATAN[$g->first()->jenis_kegiatan] ?? $g->first()->jenis_kegiatan,
                    'count' => $g->count()
                ]),

                // 12: Lokasi Kegiatan
                'per_lokasi' => $kegiatan->groupBy('lokasi_kegiatan')->map(fn($g) => $g->count()),
            ]
        ]);
    }
}
