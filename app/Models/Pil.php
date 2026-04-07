<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pil extends Model
{
    protected $guarded = ['id'];

    public function participants()
    {
        return $this->hasMany(PilParticipant::class, 'pil_id');
    }

    public function recalculateSummaries()
    {
        $participants = $this->participants;
        $count = $participants->count();

        if ($count > 0) {
            $this->update([
                'jumlah_peserta'                => $count,
                'rata_pemahaman'                => $participants->avg('nilai_pemahaman') ?: 0,
                
                // Efektifitas (a-f mapping)
                'count_sangat_efektif'          => $participants->whereIn('efektifitas_sosialisasi', ['f. Sangat Memuaskan', 'e. Memuaskan'])->count(),
                'count_efektif'                 => $participants->whereIn('efektifitas_sosialisasi', ['d. Cukup Memuaskan', 'c. Kurang Memuaskan'])->count(),
                'count_kurang_efektif'          => $participants->whereIn('efektifitas_sosialisasi', ['b. Tidak Memuaskan', 'a. Sangat Tidak Memuaskan'])->count(),
                
                // Segmen breakdown
                'count_seg_pbpu'                => $participants->where('segmen_peserta', 'PBPU')->count(),
                'count_seg_bp'                  => $participants->where('segmen_peserta', 'BP')->count(),
                'count_seg_ppu_bu'              => $participants->where('segmen_peserta', 'PPU BU')->count(),
                'count_seg_ppu_pem'             => $participants->where('segmen_peserta', 'PPU Pemerintah')->count(),
                'count_seg_pbi_apbn'            => $participants->where('segmen_peserta', 'PBI APBN')->count(),
                'count_seg_pbi_apbd'            => $participants->where('segmen_peserta', 'PBI APBD')->count(),

                'rata_nps_ketertarikan'         => $participants->avg('nps_ketertarikan') ?: 0,
                'rata_nps_rekomendasi_program'  => $participants->avg('nps_rekomendasi_program') ?: 0,
                'rata_nps_rekomendasi_bpjs'     => $participants->avg('nps_rekomendasi_bpjs') ?: 0,
            ]);
        }
    }

    public function provinsi() { return $this->belongsTo(Province::class, 'provinsi_id'); }
    public function kota() { return $this->belongsTo(City::class, 'kota_id'); }
    public function kecamatan() { return $this->belongsTo(District::class, 'kecamatan_id'); }

    /**
     * Automatic Status Calculation based on BRD (Logic Step 9)
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->status === 'canceled') return 'Dibatalkan';
        if ($this->status === 'completed') return 'Selesai';

        $now = now();
        $tanggal = \Carbon\Carbon::parse($this->tanggal);
        
        if ($tanggal->isFuture()) return 'Terjadwal';
        if ($tanggal->isPast()) return 'Selesai';
        
        // If today, check hours
        $currentTime = $now->format('H:i:s');
        if($this->jam_mulai && $currentTime < $this->jam_mulai) return 'Terjadwal';
        if($this->jam_selesai && $currentTime > $this->jam_selesai) return 'Selesai';
        
        return 'Berlangsung';
    }
}
