<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PilKegiatan extends Model
{
    protected $table = 'pil_kegiatan';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
    ];



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

    public function getLokasILengkapAttribute(): string
    {
        return implode(', ', array_filter([
            $this->nama_desa,
            $this->kecamatan?->name,
            $this->kota?->name,
            $this->provinsi?->name,
        ]));
    }
}
