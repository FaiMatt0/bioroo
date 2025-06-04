<!DOCTYPE html>
<html>
<head>
    <title>Product Creation Test - No Auth</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .info { background: #d1ecf1; color: #0c5460; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto; }
        form { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Product Creation Test - Bypass Authentication</h1>
        
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        
        // Load required files
        require_once 'config/config.php';
        require_once 'config/database.php';
        require_once 'models/Product.php';
        require_once 'models/Category.php';
        require_once 'utils/helpers.php';
        require_once 'utils/validator.php';
        
        // Start session and set admin status
        session_start();
        $_SESSION['user_id'] = 1;
        $_SESSION['is_admin'] = true;
        $_SESSION['role'] = 'admin';
        
        echo "<div class='test-section info'>";
        echo "<h3>Session Status</h3>";
        echo "User ID: " . $_SESSION['user_id'] . "<br>";
        echo "Is Admin: " . ($_SESSION['is_admin'] ? 'Yes' : 'No') . "<br>";
        echo "Role: " . $_SESSION['role'] . "<br>";
        echo "isLoggedIn(): " . (isLoggedIn() ? 'Yes' : 'No') . "<br>";
        echo "isAdmin(): " . (isAdmin() ? 'Yes' : 'No') . "<br>";
        echo "</div>";
        
        // Test database connection
        echo "<div class='test-section'>";
        echo "<h3>Database Connection Test</h3>";
        try {
            $conn = getDBConnection();
            echo "<span class='success'>✓ Database connected successfully</span><br>";
            
            // Check users table
            $userResult = $conn->query("SELECT id, first_name, last_name, role FROM users LIMIT 1");
            if ($userResult && $userResult->num_rows > 0) {
                $user = $userResult->fetch_assoc();
                echo "<span class='success'>✓ Users table accessible</span><br>";
                echo "Sample user: {$user['first_name']} {$user['last_name']} (ID: {$user['id']}, Role: {$user['role']})<br>";
            } else {
                echo "<span class='error'>✗ No users found in database</span><br>";
            }
            
            // Check categories
            $categoryResult = $conn->query("SELECT COUNT(*) as count FROM categories");
            if ($categoryResult) {
                $count = $categoryResult->fetch_assoc();
                echo "<span class='success'>✓ Categories table accessible (" . $count['count'] . " categories)</span><br>";
            }
            
        } catch (Exception $e) {
            echo "<span class='error'>✗ Database error: " . $e->getMessage() . "</span>";
        }
        echo "</div>";
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<div class='test-section'>";
            echo "<h3>Product Creation Result</h3>";
            
            try {
                // Initialize models
                $productModel = new Product();
                $categoryModel = new Category();
                
                // Process form data exactly like in adminStore
                $name = sanitize($_POST['name']);
                $description = sanitize($_POST['description']);
                $price = (float)$_POST['price'];
                $stockQuantity = (int)$_POST['stock_quantity'];
                $categoryId = (int)$_POST['category_id'];
                $status = sanitize($_POST['status'] ?? 'active');
                
                echo "<strong>Processing data:</strong><br>";
                echo "Name: '$name'<br>";
                echo "Description: '$description'<br>";
                echo "Price: $price<br>";
                echo "Stock: $stockQuantity<br>";
                echo "Category ID: $categoryId<br>";
                echo "Status: '$status'<br><br>";
                
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
                    echo "<div class='error'>";
                    echo "<strong>Validation errors:</strong><br>";
                    foreach ($errors as $field => $error) {
                        echo "$field: $error<br>";
                    }
                    echo "</div>";
                } else {
                    echo "<span class='success'>✓ Validation passed</span><br><br>";
                    
                    // Handle image (simulate)
                    $image = 'test_product.jpg';
                    
                    // Prepare product data exactly like in adminStore
                    $productData = [
                        'name' => $name,
                        'description' => $description,
                        'price' => $price,
                        'stock_quantity' => $stockQuantity,
                        'category_id' => $categoryId,
                        'user_id' => $_SESSION['user_id'],
                        'image' => $image,
                        'status' => $status
                    ];
                    
                    echo "<strong>Product data to insert:</strong><br>";
                    echo "<pre>" . print_r($productData, true) . "</pre>";
                    
                    // Create product
                    $productId = $productModel->create($productData);
                    
                    if ($productId) {
                        echo "<div class='success'>";
                        echo "<strong>✓ Product created successfully!</strong><br>";
                        echo "Product ID: $productId<br>";
                        
                        // Verify creation
                        $createdProduct = $productModel->getById($productId);
                        if ($createdProduct) {
                            echo "<strong>Verified product details:</strong><br>";
                            echo "<pre>" . print_r($createdProduct, true) . "</pre>";
                        }
                        echo "</div>";
                    } else {
                        echo "<div class='error'>";
                        echo "<strong>✗ Product creation failed</strong><br>";
                        
                        // Check for database errors
                        if (isset($productModel->conn) && $productModel->conn->error) {
                            echo "MySQL Error: " . $productModel->conn->error . "<br>";
                            echo "MySQL Errno: " . $productModel->conn->errno . "<br>";
                        }
                        echo "</div>";
                    }
                }
                
            } catch (Exception $e) {
                echo "<div class='error'>";
                echo "<strong>Exception occurred:</strong><br>";
                echo $e->getMessage() . "<br>";
                echo "<pre>" . $e->getTraceAsString() . "</pre>";
                echo "</div>";
            }
            
            echo "</div>";
        }
        ?>
        
        <form method="POST">
            <h3>Create Test Product</h3>
            
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" value="Test Product <?= date('H:i:s') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="3" required>This is a test product for debugging the creation issue.</textarea>
            </div>
            
            <div class="form-group">
                <label for="price">Price (€):</label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="19.99" required>
            </div>
            
            <div class="form-group">
                <label for="stock_quantity">Stock Quantity:</label>
                <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="15" required>
            </div>
            
            <div class="form-group">
                <label for="category_id">Category:</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php
                    try {
                        $conn = getDBConnection();
                        $result = $conn->query("SELECT id, name FROM categories ORDER BY name");
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['id']}'>{$row['name']}</option>";
                            }
                        }
                    } catch (Exception $e) {
                        echo "<option value='1'>Default Category</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            
            <button type="submit">Create Product</button>
        </form>
        
        <div class="test-section info">
            <h3>Current Products in Database</h3>
            <?php
            try {
                $conn = getDBConnection();
                $result = $conn->query("SELECT id, name, price, stock_quantity, status, created_at FROM products ORDER BY id DESC LIMIT 5");
                if ($result && $result->num_rows > 0) {
                    echo "<table border='1' style='width:100%; border-collapse: collapse;'>";
                    echo "<tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Status</th><th>Created</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['name']}</td>";
                        echo "<td>€{$row['price']}</td>";
                        echo "<td>{$row['stock_quantity']}</td>";
                        echo "<td>{$row['status']}</td>";
                        echo "<td>{$row['created_at']}</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No products found in database.";
                }
            } catch (Exception $e) {
                echo "Error retrieving products: " . $e->getMessage();
            }
            ?>
        </div>
    </div>
</body>
</html>
