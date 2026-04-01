<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpjsKeliling extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    const JENIS_KEGIATAN = [
        'goes_to_village' => 'Goes To Village',
        'around_city'     => 'Around City',
        'goes_to_office'  => 'Goes To Office',
        'institusi'       => 'Kunjungan Institusi',
        'pameran'         => 'Pameran / Event',
        'other'           => 'Lainnya',
    ];

    const STATUS = [
        'scheduled' => 'Terjadwal',
        'ongoing'   => 'Berlangsung',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    public function getTotalLayananAttribute()
    {
        return $this->layanan_informasi + $this->layanan_administrasi + $this->layanan_pengaduan;
    }

    public function getSuPelAttribute()
    {
        $totalInput = $this->kepuasan_puas + $this->kepuasan_tidak_puas;
        return $totalInput > 0 ? round(($this->kepuasan_puas / $totalInput) * 100, 2) : 0;
    }

    public function provinsi()
    {
        return $this->belongsTo(Province::class, 'provinsi_id');
    }

    public function kota()
    {
        return $this->belongsTo(City::class, 'kota_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(District::class, 'kecamatan_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(AdminUser::class, 'created_by');
    }

    public function getJenisKegiataLabelAttribute(): string
    {
        return self::JENIS_KEGIATAN[$this->jenis_kegiatan] ?? $this->jenis_kegiatan;
    }

    public function getLokasLengkapAttribute(): string
    {
        return implode(', ', array_filter([
            $this->nama_desa,
            $this->kecamatan?->name,
            $this->kota?->name,
            $this->provinsi?->name,
        ]));
    }
}
