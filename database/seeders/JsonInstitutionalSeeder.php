<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\KantorCabang;
use App\Models\KedeputianWilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class JsonInstitutionalSeeder extends Seeder
{
    public function run()
    {
        $path = public_path('refkc.json');
        if (!File::exists($path)) {
            $this->command->error("File refkc.json tidak ditemukan di folder public!");
            return;
        }

        $data = json_decode(File::get($path), true);
        $count = 0;

        foreach ($data as $item) {
            // Skip Kantor Pusat if needed, or handle it
            if ($item['KDKR'] == 0 && $item['NMKR'] == 'KANTOR PUSAT') {
                if ($item['KDKC'] == 0) continue; // Skip the generic KP
            }

            // 1. Get or Create Kedeputian Wilayah by Name to avoid unique constraint issues
            $dep = KedeputianWilayah::where('name', $item['NMKR'])->first();
            if (!$dep) {
                $dep = KedeputianWilayah::create([
                    'id' => $item['KDKR'],
                    'name' => $item['NMKR']
                ]);
            }

            // 2. Try to find matching city for better data quality
            $cityName = $item['NMKC'];
            $city = City::where('name', 'LIKE', '%' . $cityName . '%')->first();

            // 3. Update/Create Kantor Cabang
            KantorCabang::updateOrCreate(
                ['id' => $item['KDKC']],
                [
                    'name' => $cityName,
                    'kedeputian_wilayah_id' => $dep->id,
                    'province_id' => $city ? $city->province_id : null,
                    'city_id' => $city ? $city->id : null,
                ]
            );

            $count++;
        }

        $this->command->info("Berhasil migrasi $count Kantor Cabang dari JSON.");
    }
}
