<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BpjsKeliling;
use Illuminate\Http\Request;

class SurveiController extends Controller
{
    public function index($id)
    {
        $kegiatan = BpjsKeliling::findOrFail($id);
        
        // Hanya bisa isi survei jika belum cancelled
        if ($kegiatan->status === 'cancelled') {
            abort(404, 'Survei tidak tersedia karena kegiatan telah dibatalkan.');
        }

        return view('survei', compact('kegiatan'));
    }

    public function store(Request $request, $id)
    {
        $kegiatan = BpjsKeliling::findOrFail($id);

        if ($kegiatan->status === 'cancelled') {
            abort(404, 'Survei tidak tersedia karena kegiatan telah dibatalkan.');
        }

        $request->validate([
            'suara_pelanggan' => 'required|in:Puas,Tidak puas'
        ]);

        // Generate 16 digit dummy NIK (Format: 99 + ymdHis + 2 random digits)
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
        
        $kegiatan->recalculateSummaries();

        return redirect()->back()->with('success', 'Terima kasih atas penilaian Anda!');
    }
}
