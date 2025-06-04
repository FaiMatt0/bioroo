<?php
echo "=== Simple Database Test ===\n";

// Check if config files exist
echo "1. Checking config files...\n";
if (file_exists('config/database.php')) {
    echo "✓ config/database.php exists\n";
    require_once 'config/database.php';
} else {
    echo "✗ config/database.php not found\n";
    exit;
}

if (file_exists('models/Product.php')) {
    echo "✓ models/Product.php exists\n";
} else {
    echo "✗ models/Product.php not found\n";
    exit;
}

// Test database connection
echo "\n2. Testing database connection...\n";
echo "DB_HOST: " . (defined('DB_HOST') ? DB_HOST : 'undefined') . "\n";
echo "DB_NAME: " . (defined('DB_NAME') ? DB_NAME : 'undefined') . "\n";
echo "DB_USER: " . (defined('DB_USER') ? DB_USER : 'undefined') . "\n";

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Database connection successful\n";
    
    // Test simple query
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✓ Products table accessible, contains " . $result['count'] . " records\n";
    
} catch (PDOException $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
?>
