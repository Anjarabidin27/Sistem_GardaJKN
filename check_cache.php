<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cache = \Illuminate\Support\Facades\Cache::get('provinces_all');
if ($cache) {
    echo "Cache Found. Count: " . $cache->count() . "\n";
} else {
    echo "Cache Not Found.\n";
}
