<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Province;
use App\Models\City;
use App\Models\KantorCabang;

$p = Province::where('name', 'LIKE', '%YOGYAKARTA%')->first();
$c = City::where('name', 'LIKE', '%YOGYAKARTA%')->first();

echo "Prov: " . ($p ? $p->name . " ID: " . $p->id : 'NOT FOUND') . "\n";
echo "City: " . ($c ? $c->name . " ID: " . $c->id : 'NOT FOUND') . "\n";

if ($p && $c) {
    $updated = KantorCabang::where('name', 'LIKE', '%YOGYAKARTA%')
        ->update(['province_id' => $p->id, 'city_id' => $c->id]);
    echo "Updated KC YOGYAKARTA: $updated rows\n";
}

// Also try to find other KCs that have empty IDs and try to match them
$emptyKcs = KantorCabang::whereNull('province_id')->get();
foreach ($emptyKcs as $kc) {
    $matchCity = City::where('name', 'LIKE', '%' . $kc->name . '%')->first();
    if ($matchCity) {
        $kc->update(['city_id' => $matchCity->id, 'province_id' => $matchCity->province_id]);
        echo "Auto-matched KC {$kc->name} to City {$matchCity->name}\n";
    }
}
