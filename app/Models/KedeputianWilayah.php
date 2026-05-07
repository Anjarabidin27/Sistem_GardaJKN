<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KedeputianWilayah extends Model
{
    protected $fillable = ['name'];

    public function kantorCabangs()
    {
        return $this->hasMany(KantorCabang::class);
    }

    public function adminUsers()
    {
        return $this->hasManyThrough(AdminUser::class, KantorCabang::class);
    }
}
