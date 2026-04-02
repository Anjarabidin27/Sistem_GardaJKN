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
                'rata_nps_ketertarikan'         => $participants->avg('nps_ketertarikan'),
                'rata_nps_rekomendasi_program'  => $participants->avg('nps_rekomendasi_program'),
                'rata_nps_rekomendasi_bpjs'     => $participants->avg('nps_rekomendasi_bpjs'),
            ]);
        }
    }

    public function provinsi() { return $this->belongsTo(Province::class, 'provinsi_id'); }
    public function kota() { return $this->belongsTo(City::class, 'kota_id'); }
    public function kecamatan() { return $this->belongsTo(District::class, 'kecamatan_id'); }
}
