<?php
// Debug script to test product creation
require_once 'config/database.php';
require_once 'models/Product.php';

echo "=== Product Creation Debug Test ===\n";

// Test 1: Check database connection
echo "\n1. Testing database connection...\n";
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Database connection successful\n";
} catch (PDOException $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit;
}

// Test 2: Check products table structure
echo "\n2. Checking products table structure...\n";
try {
    $stmt = $pdo->query("DESCRIBE products");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Products table columns:\n";
    foreach ($columns as $column) {
        echo "  - {$column['Field']} ({$column['Type']}) {$column['Null']} {$column['Key']}\n";
    }
} catch (PDOException $e) {
    echo "✗ Error checking table structure: " . $e->getMessage() . "\n";
}

// Test 3: Test Product model creation directly
echo "\n3. Testing Product model creation...\n";
$productModel = new Product($pdo);

// Test data
$testData = [
    'name' => 'Test Product Debug',
    'description' => 'This is a test product for debugging',
    'price' => 29.99,
    'stock_quantity' => 10,
    'category_id' => 1,
    'status' => 'active',
    'image_url' => 'test.jpg'
];

echo "Test data:\n";
print_r($testData);

try {
    echo "\nAttempting to create product...\n";
    $result = $productModel->create($testData);
    echo "Result: ";
    var_dump($result);
    
    if ($result) {
        echo "✓ Product created successfully with ID: $result\n";
        
        // Verify it was inserted
        echo "\n4. Verifying product was inserted...\n";
        $insertedProduct = $productModel->getById($result);
        if ($insertedProduct) {
            echo "✓ Product found in database:\n";
            print_r($insertedProduct);
        } else {
            echo "✗ Product not found in database despite successful creation\n";
        }
    } else {
        echo "✗ Product creation returned false\n";
    }
} catch (Exception $e) {
    echo "✗ Error creating product: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

// Test 4: Check if there are any existing products
echo "\n5. Checking existing products count...\n";
try {
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total products in database: " . $count['count'] . "\n";
    
    if ($count['count'] > 0) {
        echo "\nLast 3 products:\n";
        $stmt = $pdo->query("SELECT id, name, created_at FROM products ORDER BY id DESC LIMIT 3");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as $product) {
            echo "  ID: {$product['id']}, Name: {$product['name']}, Created: {$product['created_at']}\n";
        }
    }
} catch (PDOException $e) {
    echo "✗ Error checking products: " . $e->getMessage() . "\n";
}

echo "\n=== Debug Test Complete ===\n";
?>
