<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpjsKelilingLaporan extends Model
{
    protected $table = 'bpjs_keliling_laporan';

    protected $fillable = [
        'bpjs_keliling_id',
        'layanan_informasi',
        'layanan_administrasi',
        'layanan_pengaduan',
        'jumlah_peserta',
        'kepuasan_puas',
        'kepuasan_tidak_puas',
        'catatan',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(BpjsKeliling::class, 'bpjs_keliling_id');
    }

    public function getPersenPuasAttribute(): float
    {
        $total = $this->kepuasan_puas + $this->kepuasan_tidak_puas;
        return $total > 0 ? round(($this->kepuasan_puas / $total) * 100, 1) : 0;
    }
}
