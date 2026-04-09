<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $province = \App\Models\Province::where('name', 'KALIMANTAN TENGAH')->first();

        if ($province) {
            $cities = [
                ['name' => 'KOTA PALANGKA RAYA', 'code' => '6271'],
                ['name' => 'KABUPATEN BARITO SELATAN', 'code' => '6204'],
                ['name' => 'KABUPATEN BARITO TIMUR', 'code' => '6213'],
                ['name' => 'KABUPATEN BARITO UTARA', 'code' => '6205'],
                ['name' => 'KABUPATEN GUNUNG MAS', 'code' => '6210'],
                ['name' => 'KABUPATEN KAPUAS', 'code' => '6203'],
                ['name' => 'KABUPATEN KATINGAN', 'code' => '6206'],
                ['name' => 'KABUPATEN KOTAWARINGIN BARAT', 'code' => '6201'],
                ['name' => 'KABUPATEN KOTAWARINGIN TIMUR', 'code' => '6202'],
                ['name' => 'KABUPATEN LAMANDAU', 'code' => '6209'],
                ['name' => 'KABUPATEN MURUNG RAYA', 'code' => '6212'],
                ['name' => 'KABUPATEN PULANG PISAU', 'code' => '6211'],
                ['name' => 'KABUPATEN SUKAMARA', 'code' => '6208'],
                ['name' => 'KABUPATEN SERUYAN', 'code' => '6207']
            ];

            foreach ($cities as $city) {
                \App\Models\City::updateOrCreate(
                    [ 'code' => $city['code'] ],
                    [
                        'province_id' => $province->id,
                        'name' => $city['name'],
                        'code' => $city['code']
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse as it's data seeding
    }
};
