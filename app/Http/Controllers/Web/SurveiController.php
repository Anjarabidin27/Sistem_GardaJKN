<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BpjsKeliling;
use Illuminate\Http\Request;

class SurveiController extends Controller
{
    public function index(Request $request, $id)
    {
        $kegiatan = BpjsKeliling::findOrFail($id);
        
        if ($kegiatan->status === 'cancelled') {
            abort(404, 'Survei tidak tersedia karena kegiatan telah dibatalkan.');
        }

        $participant = null;
        if ($request->has('token')) {
            try {
                $decrypted = \Illuminate\Support\Facades\Crypt::decryptString($request->token);
                // Format token: nik|activity_id
                $parts = explode('|', $decrypted);
                if (count($parts) === 2 && (int)$parts[1] === (int)$id) {
                    $participant = $kegiatan->participants()->where('nik', $parts[0])->first();
                }
            } catch (\Exception $e) {
                // Token invalid
            }
        }

        return view('survei', compact('kegiatan', 'participant'));
    }

    public function store(Request $request, $id)
    {
        $kegiatan = BpjsKeliling::findOrFail($id);

        if ($kegiatan->status === 'cancelled') {
            abort(404, 'Survei tidak tersedia karena kegiatan telah dibatalkan.');
        }

        $request->validate([
            'suara_pelanggan' => 'required|in:Puas,Tidak puas',
            'nik' => 'nullable|string'
        ]);

        if ($request->nik) {
            // Update existing participant record (Non-anonymous)
            $participant = $kegiatan->participants()->where('nik', $request->nik)->first();
            if ($participant) {
                $participant->update([
                    'suara_pelanggan' => $request->suara_pelanggan
                ]);
            }
        } else {
            // Fallback for anonymous legacy QR
            $dummyNik = '99' . date('ymdHis') . rand(10, 99);
            $kegiatan->participants()->create([
                'nik' => $dummyNik,
                'name' => 'Anonim (Survei QR)',
                'jenis_layanan' => 'Informasi', 
                'status' => 'Berhasil',
                'suara_pelanggan' => $request->suara_pelanggan,
                'jam_mulai' => date('H:i'),
                'jam_selesai' => date('H:i'),
            ]);
        }
        
        $kegiatan->recalculateSummaries();

        return redirect()->back()->with('success', 'Terima kasih atas penilaian Anda!');
    }
}
