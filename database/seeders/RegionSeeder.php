<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks for clean seed
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Province::truncate();
        City::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "Fetching Provinces from API...\n";
        
        try {
            $respProv = Http::withOptions(['verify' => false])
                ->get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');

            if ($respProv->successful()) {
                $provinces = $respProv->json();
                
                foreach ($provinces as $p) {
                    $province = Province::create([
                        'code' => $p['id'],
                        'name' => strtoupper($p['name'])
                    ]);

                    echo "  -> Fetching Cities for {$province->name}...\n";
                    
                    $respCity = Http::withOptions(['verify' => false])
                        ->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$province->code}.json");
                    
                    if ($respCity->successful()) {
                        $cities = $respCity->json();
                        $cityInsert = [];
                        
                        foreach ($cities as $c) {
                            $name = strtoupper($c['name']);
                            $type = str_contains($name, 'KOTA') ? 'KOTA' : 'KABUPATEN';
                            // Clean prefix KABUPATEN/KOTA from name if needed, but keeping it simple for now
                            $cityName = trim(str_replace(['KABUPATEN ', 'KOTA '], '', $name));

                            $cityInsert[] = [
                                'province_id' => $province->id,
                                'code' => $c['id'],
                                'name' => $cityName,
                                'type' => $type,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                        City::insert($cityInsert);
                    }
                }
                echo "Region seeding completed successfully!\n";
            }
        } catch (\Exception $e) {
            echo "Error seeding regions: " . $e->getMessage() . "\n";
        }
    }
}
