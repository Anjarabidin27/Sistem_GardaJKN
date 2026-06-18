<?php

use App\Models\Member;
use App\Models\KantorCabang;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = 0;
$members = Member::all();

foreach ($members as $m) {
    // Find the correct branch for this member's city
    $correctKC = KantorCabang::where('city_id', $m->city_id)->first();
    
    if ($correctKC && $m->kantor_cabang_id != $correctKC->id) {
        $oldKC = $m->kantor_cabang_id;
        $m->kantor_cabang_id = $correctKC->id;
        $m->save();
        echo "Fixed: {$m->name} (NIK: {$m->nik}) moved from KC ID {$oldKC} to KC ID {$correctKC->id} ({$correctKC->name})\n";
        $count++;
    }
}

echo "\nDone! Total records repaired: {$count}\n";
