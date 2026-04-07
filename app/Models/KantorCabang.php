<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KantorCabang extends Model
{
    protected $fillable = ['kedeputian_wilayah_id', 'province_id', 'city_id', 'code', 'name'];

    public function kedeputianWilayah()
    {
        return $this->belongsTo(KedeputianWilayah::class);
    }
}
