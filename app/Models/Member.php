<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Member extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nik',
        'jkn_number',
        'password',
        'name',
        'role',
        'status_pengurus',
        'phone',
        'birth_date',
        'gender',
        'education',
        'occupation',
        'address_detail',
        'province_id',
        'city_id',
        'district_id',
        'dom_province_id',
        'dom_city_id',
        'dom_district_id',
        'dom_address_detail',
        'photo_path',
        'is_interested_pengurus',
        'has_org_experience',
        'org_count',
        'org_name',
        'org_position',
        'org_duration_months',
        'org_description',
        'pengurus_reason',
        'org_certificate_path',
    ];

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute()
    {
        if ($this->photo_path) {
            $path = str_replace('\\', '/', $this->photo_path);
            return asset('storage/' . $path);
        }
        // Identicon or Default Avatar
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=004aad&color=fff&size=200';
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
