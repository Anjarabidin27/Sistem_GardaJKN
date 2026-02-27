<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncRegionData extends Command
{
    protected $signature = 'region:sync';
    protected $description = 'Sync Indonesia regions (Provinces, Cities, Districts) from official API';

    public function handle()
    {
        $this->info('Starting Indonesia Region Sync...');

        try {
            $pResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                ->get("https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json");
            
            if (!$pResponse->successful()) {
                $this->error('Unable to reach provinces API');
                return 1;
            }
            
            $provinces = $pResponse->json();
            
            $this->warn('TRUNCATING REGION TABLES...');
            if (config('database.default') === 'mysql') {
                \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                \Illuminate\Support\Facades\DB::table('districts')->truncate();
                \Illuminate\Support\Facades\DB::table('cities')->truncate();
                \Illuminate\Support\Facades\DB::table('provinces')->truncate();
                \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            } else {
                \Illuminate\Support\Facades\DB::statement('TRUNCATE provinces, cities, districts RESTART IDENTITY CASCADE');
            }

            $bar = $this->output->createProgressBar(count($provinces));
            $bar->start();

            foreach ($provinces as $pData) {
                $prov = \App\Models\Province::create([
                    'code' => $pData['id'],
                    'name' => strtoupper($pData['name'])
                ]);

                $rResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                    ->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$pData['id']}.json");
                
                if ($rResponse->successful()) {
                    foreach ($rResponse->json() as $rData) {
                        $nameRaw = strtoupper($rData['name']);
                        $city = \App\Models\City::create([
                            'province_id' => $prov->id,
                            'code' => $rData['id'],
                            'name' => $nameRaw,
                            'type' => str_contains($nameRaw, 'KABUPATEN') ? 'KABUPATEN' : 'KOTA'
                        ]);

                        $dResponse = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                            ->get("https://emsifa.github.io/api-wilayah-indonesia/api/districts/{$rData['id']}.json");
                        
                        if ($dResponse->successful()) {
                            $districts = array_map(fn($d) => [
                                'city_id' => $city->id,
                                'code' => $d['id'],
                                'name' => strtoupper($d['name']),
                                'created_at' => now(),
                                'updated_at' => now()
                            ], $dResponse->json());
                            
                            \App\Models\District::insert($districts);
                        }
                    }
                }
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();
            
            \Illuminate\Support\Facades\Cache::flush();
            $this->info('Sync Completed Successfully!');
            return 0;

        } catch (\Exception $e) {
            $this->error('Fatal Error: ' . $e->getMessage());
            return 1;
        }
    }
}
