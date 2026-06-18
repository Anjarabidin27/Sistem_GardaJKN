<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Member;

echo "--- 5 PENDAFTAR TERBARU ---\n";
$latest = Member::with(['city', 'kantorCabang'])->latest()->take(5)->get();
foreach ($latest as $m) {
    echo "Nama: " . str_pad($m->name, 20);
    echo " | Kota: " . str_pad($m->city?->name ?? 'N/A', 25);
    echo " | Cabang: " . ($m->kantorCabang?->name ?? '--- KOSONG ---') . "\n";
}
