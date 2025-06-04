<!DOCTYPE html>
<html>
<head>
    <title>Product Store Debug</title>
    <style>
        body { font-family: monospace; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        .debug { background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 4px; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Product Store Debug Test</h1>
    
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Load required files
    require_once 'config/config.php';
    require_once 'config/database.php';
    require_once 'models/Product.php';
    require_once 'utils/helpers.php';
    require_once 'utils/validator.php';
    
    echo "<div class='info'><strong>Testing Product Creation Process</strong></div><br>";
    
    // Simulate form data
    $_POST = [
        'name' => 'Debug Test Product ' . date('H:i:s'),
        'description' => 'This is a test product created during debugging',
        'price' => '99.99',
        'stock_quantity' => '5',
        'category_id' => '1',
        'status' => 'active'
    ];
    
    // Simulate session data
    session_start();
    $_SESSION['user_id'] = 1; // Assuming admin user ID is 1
    $_SESSION['role'] = 'admin';
    
    echo "<div class='debug'><strong>Simulated POST data:</strong><br>";
    echo "<pre>" . print_r($_POST, true) . "</pre></div>";
    
    echo "<div class='debug'><strong>Simulated SESSION data:</strong><br>";
    echo "<pre>User ID: " . $_SESSION['user_id'] . "<br>Role: " . $_SESSION['role'] . "</pre></div>";
    
    try {
        // Initialize Product model
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $productModel = new Product($pdo);
        
        echo "<div class='success'>✓ Product model initialized</div>";
        
        // Process the data like in adminStore method
        $name = sanitize($_POST['name']);
        $description = sanitize($_POST['description']);
        $price = (float)$_POST['price'];
        $stockQuantity = (int)$_POST['stock_quantity'];
        $categoryId = (int)$_POST['category_id'];
        $status = sanitize($_POST['status'] ?? 'active');
        
        echo "<div class='debug'><strong>Processed data:</strong><br>";
        echo "Name: '$name'<br>";
        echo "Description: '$description'<br>";
        echo "Price: $price<br>";
        echo "Stock: $stockQuantity<br>";
        echo "Category ID: $categoryId<br>";
        echo "Status: '$status'<br>";
        echo "</div>";
        
        // Validation
        $errors = [];
        
        if (!validateRequired($name)) {
            $errors['name'] = "Nome prodotto richiesto";
        }
        
        if (!validatePrice($price)) {
            $errors['price'] = "Prezzo non valido";
        }
        
        if (!validatePositiveInt($stockQuantity)) {
            $errors['stock_quantity'] = "Quantità non valida";
        }
        
        if (!empty($errors)) {
            echo "<div class='error'><strong>Validation errors:</strong><br>";
            foreach ($errors as $field => $error) {
                echo "$field: $error<br>";
            }
            echo "</div>";
        } else {
            echo "<div class='success'>✓ Validation passed</div>";
            
            // Prepare product data
            $productData = [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'stock_quantity' => $stockQuantity,
                'category_id' => $categoryId,
                'user_id' => $_SESSION['user_id'],
                'image' => 'debug_test.jpg',
                'status' => $status
            ];
            
            echo "<div class='debug'><strong>Product data for creation:</strong><br>";
            echo "<pre>" . print_r($productData, true) . "</pre></div>";
            
            // Attempt to create product
            echo "<div class='info'>Attempting to create product...</div>";
            $productId = $productModel->create($productData);
            
            if ($productId) {
                echo "<div class='success'>✓ Product created successfully with ID: $productId</div>";
                
                // Verify it exists
                $createdProduct = $productModel->getById($productId);
                if ($createdProduct) {
                    echo "<div class='success'>✓ Product verified in database</div>";
                    echo "<div class='debug'><strong>Created product details:</strong><br>";
                    echo "<pre>" . print_r($createdProduct, true) . "</pre></div>";
                } else {
                    echo "<div class='error'>✗ Product not found after creation</div>";
                }
            } else {
                echo "<div class='error'>✗ Product creation failed (returned false)</div>";
                
                // Check for database errors
                echo "<div class='debug'><strong>Database connection error info:</strong><br>";
                if ($productModel->conn && $productModel->conn->error) {
                    echo "MySQL Error: " . $productModel->conn->error . "<br>";
                    echo "MySQL Error Number: " . $productModel->conn->errno . "<br>";
                } else {
                    echo "No MySQL error information available<br>";
                }
                echo "</div>";
            }
        }
        
    } catch (Exception $e) {
        echo "<div class='error'>✗ Exception occurred: " . $e->getMessage() . "</div>";
        echo "<div class='error'>Stack trace:</div>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
    
    echo "<br><div class='info'><strong>Debug test complete</strong></div>";
    ?>
    
</body>
</html>
