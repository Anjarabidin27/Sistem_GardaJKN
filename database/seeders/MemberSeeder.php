<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $p = Province::where('name', 'LIKE', '%JAKARTA%')->first() ?? Province::first();
        if (!$p) return;

        $city = City::where('province_id', $p->id)->first();
        if (!$city) return;

        // Ensure at least one district exists for this city
        $dist = District::where('city_id', $city->id)->first();
        if (!$dist) {
            $dist = District::create([
                'city_id' => $city->id,
                'code' => $city->code . '010',
                'name' => 'KECAMATAN CONTOH'
            ]);
        }

        $members = [
            ['nik' => '3171010101900000', 'name' => 'Vini Jr'],
            ['nik' => '3171010101900001', 'name' => 'Valverde'],
            ['nik' => '3171010101900002', 'name' => 'Bellingham'],
            ['nik' => '3171010101900003', 'name' => 'Rodrygo'],
            ['nik' => '3171010101900004', 'name' => 'Courtois'],
        ];

        foreach ($members as $m) {
            Member::updateOrCreate(
                ['nik' => $m['nik']],
                [
                    'name' => $m['name'],
                    'password' => 'GardaJKN2026!',
                    'phone' => '0812' . rand(10000000, 99999999),
                    'birth_date' => '1990-01-01',
                    'gender' => rand(0, 1) ? 'L' : 'P',
                    'education' => 'S1/D4',
                    'occupation' => 'Karyawan',
                    'address_detail' => 'Jl. Tebet No. ' . rand(1, 100),
                    'province_id' => $p->id,
                    'city_id' => $city->id,
                    'district_id' => $dist->id,
                ]
            );
        }
        echo "Member seeding completed.\n";
    }
}
