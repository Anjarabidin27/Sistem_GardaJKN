<?php

use App\Models\AdminUser;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$usernames = ['admin', 'admin_wib', 'admin_wita', 'admin_wit'];
$password = 'GardaAdmin2026!!';

foreach($usernames as $u) {
    $user = AdminUser::where('username', $u)->first();
    if($user) {
        $user->password = $password;
        $user->save();
        echo "Reset password for $u to $password\n";
    } else {
        echo "User $u not found\n";
    }
}
