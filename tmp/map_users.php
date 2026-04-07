<?php

use App\Models\AdminUser;
use App\Models\KantorCabang;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = AdminUser::whereNull('kantor_cabang_id')->get();
echo "Found " . $users->count() . " users to map.\n";

foreach($users as $u) {
    if (!$u->kantor_cabang) {
        echo "User {$u->username} has no kantor_cabang string. Skipping.\n";
        continue;
    }

    $name = str_replace('KC ', '', $u->kantor_cabang);
    $kc = KantorCabang::where('name', 'like', '%' . $name . '%')->first();

    if($kc) {
        $u->update(['kantor_cabang_id' => $kc->id]);
        echo "Mapped {$u->username} to {$kc->name} (ID: {$kc->id})\n";
    } else {
        echo "Could not map {$u->username} ({$u->kantor_cabang})\n";
    }
}
