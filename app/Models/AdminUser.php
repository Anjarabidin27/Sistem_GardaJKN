<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AdminUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admin_users';

    protected $fillable = [
        'username',
        'password',
        'name',
        'role',
        'kantor_cabang_id',
        'kedeputian_wilayah',
        'kantor_cabang',
        'zona_waktu',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function kantorCabang()
    {
        return $this->belongsTo(KantorCabang::class, 'kantor_cabang_id');
    }
}
