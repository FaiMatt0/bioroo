<!DOCTYPE html>
<html>
<head>
    <title>Product Store Debug - Correct Version</title>
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
    <h1>Product Store Debug Test - Fixed Version</h1>
    
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Load required files
    require_once 'config/config.php';
    require_once 'config/database.php';
    require_once 'models/Product.php';
    require_once 'utils/helpers.php';
    require_once 'utils/validator.php';
    
    echo "<div class='info'><strong>Testing Product Creation Process (Fixed)</strong></div><br>";
    
    // Test database connection first
    echo "<h3>1. Database Connection Test</h3>";
    try {
        $conn = getDBConnection();
        echo "<div class='success'>✓ MySQLi connection successful</div>";
        echo "<div class='info'>Connection charset: " . $conn->character_set_name() . "</div>";
        
        // Test products table
        $result = $conn->query("SELECT COUNT(*) as count FROM products");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<div class='success'>✓ Products table accessible, contains " . $row['count'] . " records</div>";
        } else {
            echo "<div class='error'>✗ Error accessing products table: " . $conn->error . "</div>";
        }
        
    } catch (Exception $e) {
        echo "<div class='error'>✗ Database connection failed: " . $e->getMessage() . "</div>";
        exit;
    }
    
    // Test Product model initialization
    echo "<h3>2. Product Model Test</h3>";
    try {
        $productModel = new Product();
        echo "<div class='success'>✓ Product model initialized successfully</div>";
    } catch (Exception $e) {
        echo "<div class='error'>✗ Product model initialization failed: " . $e->getMessage() . "</div>";
        exit;
    }
    
    // Simulate form data
    echo "<h3>3. Form Data Simulation</h3>";
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
    
    // Process data like in adminStore method
    echo "<h3>4. Data Processing</h3>";
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
    echo "<h3>5. Validation Test</h3>";
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
        
        // Test category exists
        echo "<h3>6. Category Validation</h3>";
        $categoryResult = $conn->query("SELECT id, name FROM categories WHERE id = $categoryId");
        if ($categoryResult && $categoryResult->num_rows > 0) {
            $category = $categoryResult->fetch_assoc();
            echo "<div class='success'>✓ Category exists: {$category['name']} (ID: {$category['id']})</div>";
        } else {
            echo "<div class='error'>✗ Category with ID $categoryId does not exist</div>";
            $errors['category'] = "Category not found";
        }
        
        // Test user exists
        echo "<h3>7. User Validation</h3>";
        $userId = $_SESSION['user_id'];
        $userResult = $conn->query("SELECT id, first_name, last_name FROM users WHERE id = $userId");
        if ($userResult && $userResult->num_rows > 0) {
            $user = $userResult->fetch_assoc();
            echo "<div class='success'>✓ User exists: {$user['first_name']} {$user['last_name']} (ID: {$user['id']})</div>";
        } else {
            echo "<div class='error'>✗ User with ID $userId does not exist</div>";
            $errors['user'] = "User not found";
        }
        
        if (empty($errors)) {
            // Prepare product data
            echo "<h3>8. Product Creation Test</h3>";
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
                echo "<div class='debug'><strong>Database error info:</strong><br>";
                if ($conn->error) {
                    echo "MySQL Error: " . $conn->error . "<br>";
                    echo "MySQL Error Number: " . $conn->errno . "<br>";
                } else {
                    echo "No MySQL error information available<br>";
                }
                echo "</div>";
            }
        }
    }
    
    echo "<br><div class='info'><strong>Debug test complete</strong></div>";
    ?>
    
</body>
</html>
