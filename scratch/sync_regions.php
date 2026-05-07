<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Province;
use App\Models\City;
use App\Models\KantorCabang;
use Illuminate\Support\Facades\Http;

$provinces = Province::all();
echo "Found " . $provinces->count() . " provinces. Fetching cities...\n";

foreach ($provinces as $prov) {
    echo "Processing {$prov->name} ({$prov->code})...\n";
    try {
        $response = Http::withOptions(['verify' => false, 'timeout' => 15])
            ->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$prov->code}.json");
        
        if ($response->successful()) {
            $apiData = $response->json();
            $count = 0;
            foreach ($apiData as $d) {
                City::updateOrCreate(
                    ['code' => $d['id']],
                    [
                        'province_id' => $prov->id,
                        'name' => strtoupper($d['name']),
                        'type' => str_contains(strtoupper($d['name']), 'KOTA') ? 'KOTA' : 'KABUPATEN'
                    ]
                );
                $count++;
            }
            echo " -> Added/Updated $count cities.\n";
        } else {
            echo " -> Failed to fetch cities for {$prov->name}.\n";
        }
    } catch (\Exception $e) {
        echo " -> Error: " . $e->getMessage() . "\n";
    }
}

echo "Finalizing KC mapping...\n";
$kcs = KantorCabang::all();
foreach ($kcs as $kc) {
    // Try to match by name
    $matchCity = City::where('name', 'LIKE', '%' . $kc->name . '%')->first();
    if ($matchCity) {
        $kc->update(['city_id' => $matchCity->id, 'province_id' => $matchCity->province_id]);
        echo "Matched KC {$kc->name} to {$matchCity->name}\n";
    } else {
        // Fallback for Yogyakarta
        if ($kc->name === 'YOGYAKARTA') {
            $diyCity = City::where('name', 'KOTA YOGYAKARTA')->first();
            if ($diyCity) {
                $kc->update(['city_id' => $diyCity->id, 'province_id' => $diyCity->province_id]);
                echo "Special Match KC YOGYAKARTA to KOTA YOGYAKARTA\n";
            }
        }
    }
}
echo "Done.\n";
