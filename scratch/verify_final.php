<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\KantorCabang;

$kc = KantorCabang::where('name', 'YOGYAKARTA')->first();
if ($kc) {
    echo "KC: " . $kc->name . "\n";
    echo "City: " . ($kc->city_id ? \App\Models\City::find($kc->city_id)->name : 'NULL') . "\n";
    echo "Prov: " . ($kc->province_id ? \App\Models\Province::find($kc->province_id)->name : 'NULL') . "\n";
} else {
    echo "KC NOT FOUND\n";
}
