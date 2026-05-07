<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Member;
use App\Http\Controllers\Api\Member\InformationController;
use Illuminate\Support\Facades\Auth;

$user = Member::first();
if (!$user) {
    echo "No member found\n";
    exit;
}

// Log in the user
Auth::login($user);

$controller = app(InformationController::class);
$response = $controller->index();

echo $response->getContent();
