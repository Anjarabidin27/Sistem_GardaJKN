<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SyncRegionData extends Command
{
    protected $signature = 'region:sync {--force : Truncate all before sync}';
    protected $description = 'Super Turbo Sync: Full City + District integration';

    public function handle()
    {
        $this->info('Starting SUPER TURBO Indonesia Region Sync...');

        if ($this->option('force')) {
            $this->warn('FORCE TRUNCATING TABLES...');
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            District::truncate();
            City::truncate();
            Province::truncate();
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        try {
            // STEP 1: All 34 Provinces
            $this->info('Step 1: Downloading all 34 Provinces...');
            $pResponse = Http::withOptions(['verify' => false, 'timeout' => 30])
                ->get("https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json");
            
            if (!$pResponse->successful()) return $this->error('API unreachable');
            $provinces = $pResponse->json();
            foreach ($provinces as $pData) {
                Province::firstOrCreate(
                    ['code' => $pData['id']],
                    ['name' => strtoupper($pData['name'])]
                );
            }

            // STEP 2: Cities + Districts Integrated (Best for UX)
            $this->info('Step 2: Concurrent City & District Sync...');
            $bar = $this->output->createProgressBar(count($provinces));
            $bar->start();

            foreach ($provinces as $pData) {
                $pModel = Province::where('code', $pData['id'])->first();
                
                // Fetch Cities for this Province
                $rResponse = Http::withOptions(['verify' => false, 'timeout' => 30])
                    ->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$pData['id']}.json");
                
                if ($rResponse->successful()) {
                    foreach ($rResponse->json() as $rData) {
                        // Check if city exists
                        $city = City::where('code', $rData['id'])->first();
                        if (!$city) {
                            $nameRaw = strtoupper($rData['name']);
                            $cleanName = preg_replace('/^(KABUPATEN|KOTA|KAB\.?)\s+/i', '', $nameRaw);
                            
                            $city = City::create([
                                'province_id' => $pModel->id,
                                'code' => $rData['id'],
                                'name' => $cleanName,
                                'type' => str_contains($nameRaw, 'KABUPATEN') ? 'KABUPATEN' : 'KOTA'
                            ]);
                        }

                        // IMMEDIATELY fetch Districts for this city if 0
                        if (District::where('city_id', $city->id)->count() === 0) {
                            $dResponse = Http::withOptions(['verify' => false, 'timeout' => 30])
                                ->get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$rData['id']}.json");
                            
                            if ($dResponse->successful()) {
                                $districts = array_map(fn($d) => [
                                    'city_id' => $city->id,
                                    'code' => $d['id'],
                                    'name' => strtoupper($d['name']),
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ], $dResponse->json());
                                District::insert($districts);
                            }
                        }
                    }
                }
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            Cache::flush();
            $this->info('SUPER TURBO Sync Completed!');
            return 0;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
