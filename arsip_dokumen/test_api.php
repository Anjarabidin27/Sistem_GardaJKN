<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$request = Illuminate\Http\Request::create('/api/admin/login', 'POST', [
    'username' => 'admin',
    'password' => 'password'
]);
$response = app()->handle($request);
$data = json_decode($response->getContent(), true);

echo "LOGIN:\n";
print_r($data);

if (isset($data['token'])) {
    $token = $data['token'];
    
    $req2 = Illuminate\Http\Request::create('/api/admin/bpjs-keliling', 'GET');
    $req2->headers->set('Authorization', "Bearer $token");
    $req2->headers->set('Accept', 'application/json');
    try {
        $res2 = app()->handle($req2);
        echo "\n\nBPJS KELILING (Code: " . $res2->getStatusCode() . "):\n";
        echo $res2->getContent();
    } catch (\Throwable $e) {
        echo "\n\nFATAL ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
    }
}
