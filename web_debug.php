<!DOCTYPE html>
<html>
<head>
    <title>Product Creation Debug</title>
    <style>
        body { font-family: monospace; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Product Creation Debug</h1>
    
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    echo "<div class='info'><strong>Starting debug process...</strong></div><br>";
    
    // Test 1: Check if required files exist
    echo "<h3>1. File System Check</h3>";
    $files = [
        'config/database.php' => file_exists('config/database.php'),
        'models/Product.php' => file_exists('models/Product.php'),
        'controllers/ProductController.php' => file_exists('controllers/ProductController.php')
    ];
    
    foreach ($files as $file => $exists) {
        $class = $exists ? 'success' : 'error';
        $status = $exists ? '✓' : '✗';
        echo "<div class='$class'>$status $file</div>";
    }
    
    // Test 2: Load config and test database
    echo "<h3>2. Database Connection Test</h3>";
    try {
        require_once 'config/database.php';
        echo "<div class='success'>✓ Config loaded successfully</div>";
        echo "<div class='info'>DB_HOST: " . DB_HOST . "</div>";
        echo "<div class='info'>DB_NAME: " . DB_NAME . "</div>";
        echo "<div class='info'>DB_USER: " . DB_USER . "</div>";
        
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "<div class='success'>✓ Database connection successful</div>";
        
        // Check products table
        $stmt = $pdo->query("SHOW TABLES LIKE 'products'");
        if ($stmt->rowCount() > 0) {
            echo "<div class='success'>✓ Products table exists</div>";
            
            // Check table structure
            $stmt = $pdo->query("DESCRIBE products");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<div class='info'>Products table structure:</div>";
            echo "<pre>";
            foreach ($columns as $column) {
                echo "  {$column['Field']} ({$column['Type']}) {$column['Null']} {$column['Key']}\n";
            }
            echo "</pre>";
            
            // Check current product count
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
            $count = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<div class='info'>Current products count: " . $count['count'] . "</div>";
            
        } else {
            echo "<div class='error'>✗ Products table does not exist</div>";
        }
        
    } catch (Exception $e) {
        echo "<div class='error'>✗ Database error: " . $e->getMessage() . "</div>";
    }
    
    // Test 3: Test Product model
    echo "<h3>3. Product Model Test</h3>";
    try {
        require_once 'models/Product.php';
        echo "<div class='success'>✓ Product model loaded</div>";
        
        $productModel = new Product($pdo);
        echo "<div class='success'>✓ Product model instantiated</div>";
        
        // Test data for creation
        $testData = [
            'name' => 'Debug Test Product ' . date('Y-m-d H:i:s'),
            'description' => 'This is a test product created during debugging',
            'price' => 99.99,
            'stock_quantity' => 5,
            'category_id' => 1,
            'status' => 'active',
            'image_url' => 'debug_test.jpg'
        ];
        
        echo "<div class='info'>Test data:</div>";
        echo "<pre>" . print_r($testData, true) . "</pre>";
        
        // Attempt to create product
        echo "<div class='info'>Attempting to create product...</div>";
        $result = $productModel->create($testData);
        
        if ($result) {
            echo "<div class='success'>✓ Product created successfully with ID: $result</div>";
            
            // Verify it exists
            $createdProduct = $productModel->getById($result);
            if ($createdProduct) {
                echo "<div class='success'>✓ Product verified in database</div>";
                echo "<div class='info'>Created product:</div>";
                echo "<pre>" . print_r($createdProduct, true) . "</pre>";
            } else {
                echo "<div class='error'>✗ Product not found after creation</div>";
            }
        } else {
            echo "<div class='error'>✗ Product creation failed (returned false)</div>";
        }
        
    } catch (Exception $e) {
        echo "<div class='error'>✗ Product model error: " . $e->getMessage() . "</div>";
        echo "<div class='error'>Stack trace:</div>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    
    echo "<br><div class='info'><strong>Debug complete</strong></div>";
    ?>
    
</body>
</html>
