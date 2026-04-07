<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstitutionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/refkc.json'));
        $data = json_decode($json, true);

        foreach ($data as $item) {
            $kwName = $item['NMKR'];
            $kcName = $item['NMKC'];

            // Skip if it's the regional itself registered as KC (duplicates in some XLS)
            if ($kwName === $kcName && $kwName !== 'KANTOR PUSAT') continue;

            $kw = \App\Models\KedeputianWilayah::firstOrCreate(['name' => $kwName]);
            
            \App\Models\KantorCabang::firstOrCreate([
                'kedeputian_wilayah_id' => $kw->id,
                'name' => $kcName
            ]);
        }
    }
}
