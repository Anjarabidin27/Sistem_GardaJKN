<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PilLaporan extends Model
{
    protected $table = 'pil_laporan';

    protected $fillable = [
        'pil_kegiatan_id',
        'jumlah_peserta',
        'rata_uji_pemahaman',
        'efek_ketertarikan_jkn',
        'efek_rekomendasi_jkn',
        'efek_rekomendasi_bpjs',
        'catatan',
    ];

    protected $casts = [
        'rata_uji_pemahaman' => 'decimal:2',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(PilKegiatan::class, 'pil_kegiatan_id');
    }
}
