<?php

$filesToAdmin = [
    'resources/views/admin/approvals/pengurus.blade.php',
    'resources/views/admin/audit_logs/index.blade.php',
    'resources/views/admin/informations/index.blade.php',
    'resources/views/admin/members/modals.blade.php',
    'resources/views/auth/admin_login.blade.php',
];

$filesToAnggota = [
    'resources/views/member/apply_pengurus.blade.php',
    'resources/views/member/informations/index.blade.php',
    'resources/views/member/profile.blade.php',
    'resources/views/pengurus/dashboard.blade.php',
    'resources/views/auth/login.blade.php',
    'resources/views/auth/register.blade.php',
    'resources/views/welcome.blade.php',
];

$filesToApp = [
    'resources/views/layouts/app.blade.php'
];

function extractAndAppend($files, $cssFile) {
    if (!file_exists($cssFile)) {
        file_put_contents($cssFile, "/* Auto extracted styles */\n");
    }
    
    foreach ($files as $file) {
        if (!file_exists($file)) continue;
        
        $content = file_get_contents($file);
        
        // Match <style> ... </style>
        // Note: we might have multiple style blocks
        $pattern = '/<style\b[^>]*>(.*?)<\/style>/is';
        
        if (preg_match_all($pattern, $content, $matches)) {
            foreach ($matches[1] as $styleContent) {
                // Append to css file
                file_put_contents($cssFile, "\n/* From: " . basename($file) . " */\n" . trim($styleContent) . "\n", FILE_APPEND);
            }
            
            // Remove <style> blocks from blade file
            $newContent = preg_replace($pattern, '', $content);
            file_put_contents($file, $newContent);
            echo "Extracted style from $file to $cssFile\n";
        } else {
            echo "No style tag found in $file\n";
        }
    }
}

extractAndAppend($filesToAdmin, 'resources/css/admin.css');
extractAndAppend($filesToAnggota, 'resources/css/anggota.css'); // Wait, pengurus dashboard should go to pengurus.css
// I will just append pengurus to pengurus.css
extractAndAppend(['resources/views/pengurus/dashboard.blade.php'], 'resources/css/pengurus.css');

// Adjust arrays as I moved pengurus above
$filesToAnggotaFiltered = array_diff($filesToAnggota, ['resources/views/pengurus/dashboard.blade.php']);
extractAndAppend($filesToAnggotaFiltered, 'resources/css/anggota.css');

extractAndAppend($filesToApp, 'resources/css/app.css');

echo "Done.\n";
