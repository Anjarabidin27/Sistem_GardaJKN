<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$data = app(\App\Services\DashboardService::class)->getStats(6);
file_put_contents('output_test.json', json_encode($data, JSON_PRETTY_PRINT));