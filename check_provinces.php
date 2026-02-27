<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=garda_jkn_v2", "root", "");
    $stmt = $pdo->query("SELECT COUNT(*) FROM provinces");
    echo "Total Provinces: " . $stmt->fetchColumn() . "\n";
    
    $stmt = $pdo->query("SELECT * FROM provinces LIMIT 5");
    $provinces = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($provinces as $p) {
        echo "- " . $p['name'] . "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
