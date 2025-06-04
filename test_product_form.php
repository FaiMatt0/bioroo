<!DOCTYPE html>
<html>
<head>
    <title>Direct Product Creation Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .form-container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .result { margin-top: 20px; padding: 15px; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Direct Product Creation Test</h1>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            echo "<div class='result'>";
            
            // Load required files
            require_once 'config/config.php';
            require_once 'config/database.php';
            require_once 'models/Product.php';
            require_once 'utils/helpers.php';
            require_once 'utils/validator.php';
            
            // Start session
            session_start();
            $_SESSION['user_id'] = 1; // Simulate admin user
            $_SESSION['role'] = 'admin';
            
            try {
                // Initialize Product model
                $productModel = new Product();
                
                // Process form data
                $name = sanitize($_POST['name']);
                $description = sanitize($_POST['description']);
                $price = (float)$_POST['price'];
                $stockQuantity = (int)$_POST['stock_quantity'];
                $categoryId = (int)$_POST['category_id'];
                $status = sanitize($_POST['status'] ?? 'active');
                
                echo "<strong>Processing product creation...</strong><br><br>";
                echo "Name: $name<br>";
                echo "Description: $description<br>";
                echo "Price: $price<br>";
                echo "Stock: $stockQuantity<br>";
                echo "Category ID: $categoryId<br>";
                echo "Status: $status<br><br>";
                
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
                    // Prepare product data
                    $productData = [
                        'name' => $name,
                        'description' => $description,
                        'price' => $price,
                        'stock_quantity' => $stockQuantity,
                        'category_id' => $categoryId,
                        'user_id' => $_SESSION['user_id'],
                        'image' => 'test_product.jpg',
                        'status' => $status
                    ];
                    
                    echo "<strong>Attempting to create product...</strong><br>";
                    $productId = $productModel->create($productData);
                    
                    if ($productId) {
                        echo "<div class='success'>";
                        echo "✓ Product created successfully with ID: $productId<br>";
                        
                        // Verify it exists
                        $createdProduct = $productModel->getById($productId);
                        if ($createdProduct) {
                            echo "✓ Product verified in database<br>";
                            echo "<strong>Created product:</strong><br>";
                            echo "ID: " . $createdProduct['id'] . "<br>";
                            echo "Name: " . $createdProduct['name'] . "<br>";
                            echo "Price: €" . $createdProduct['price'] . "<br>";
                            echo "Stock: " . $createdProduct['stock_quantity'] . "<br>";
                        }
                        echo "</div>";
                    } else {
                        echo "<div class='error'>";
                        echo "✗ Product creation failed<br>";
                        
                        // Get database connection to check for errors
                        $conn = getDBConnection();
                        if ($conn->error) {
                            echo "MySQL Error: " . $conn->error . "<br>";
                        }
                        echo "</div>";
                    }
                }
                
            } catch (Exception $e) {
                echo "<div class='error'>";
                echo "Exception: " . $e->getMessage() . "<br>";
                echo "</div>";
            }
            
            echo "</div>";
        }
        ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" value="Test Product <?= date('H:i:s') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="3" required>This is a test product created for debugging purposes.</textarea>
            </div>
            
            <div class="form-group">
                <label for="price">Price (€):</label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="29.99" required>
            </div>
            
            <div class="form-group">
                <label for="stock_quantity">Stock Quantity:</label>
                <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="10" required>
            </div>
            
            <div class="form-group">
                <label for="category_id">Category ID:</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php
                    try {
                        require_once 'config/database.php';
                        $conn = getDBConnection();
                        $result = $conn->query("SELECT id, name FROM categories ORDER BY name");
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['id']}'>{$row['name']}</option>";
                        }
                    } catch (Exception $e) {
                        echo "<option value='1'>Default Category (ID: 1)</option>";
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
    </div>
</body>
</html>
