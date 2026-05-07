<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\KantorCabang;
use App\Models\Province;

$kc = KantorCabang::where('name', 'LIKE', '%YOGYAKARTA%')->first();
if ($kc) {
    echo "KC: " . $kc->name . "\n";
    echo "ProvID: " . $kc->province_id . "\n";
    echo "ProvExists: " . (Province::where('id', $kc->province_id)->exists() ? 'YES' : 'NO') . "\n";
    $p = Province::where('id', $kc->province_id)->first();
    if ($p) {
        echo "ProvName: " . $p->name . "\n";
    }
} else {
    echo "KC NOT FOUND\n";
}
