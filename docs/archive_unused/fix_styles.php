<?php
$files = [
    'd:\Sistem Garda JKN\resources\views\pengurus\dashboard.blade.php',
    'd:\Sistem Garda JKN\resources\views\member\profile.blade.php',
    'd:\Sistem Garda JKN\resources\views\member\informations\index.blade.php',
    'd:\Sistem Garda JKN\resources\views\admin\members\index.blade.php',
    'd:\Sistem Garda JKN\resources\views\admin\informations\index.blade.php',
    'd:\Sistem Garda JKN\resources\views\admin\dashboard.blade.php',
    'd:\Sistem Garda JKN\resources\views\admin\approvals\pengurus.blade.php',
    'd:\Sistem Garda JKN\resources\views\admin\audit_logs\index.blade.php'
];

foreach ($files as $f) {
    if (file_exists($f)) {
        $c = file_get_contents($f);
        if (strpos($c, "@section('push_styles')") !== false) {
            $c = preg_replace("/@section\('push_styles'\)([\s\S]*?)@endsection/", "@push('styles')$1@endpush", $c, 1);
            file_put_contents($f, $c);
            echo "Fixed: $f\n";
        }
    } else {
        echo "Not found: $f\n";
    }
}
