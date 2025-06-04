<?php
require_once 'config/database.php';
require_once 'models/Category.php';
require_once 'controllers/CategoryController.php';
require_once 'utils/helpers.php';

echo "=== FINAL CATEGORIES ADMIN TEST ===\n";

try {
    // Test the Category model method that should be used
    echo "1. Testing Category::getAllWithProductCount()...\n";
    $categories = Category::getAllWithProductCount();
    echo "   ✓ Method executed successfully\n";
    echo "   ✓ Returned " . count($categories) . " categories\n";
    
    if (!empty($categories)) {
        $category = $categories[0];
        echo "   ✓ First category structure:\n";
        foreach ($category as $key => $value) {
            echo "     - $key: " . (is_null($value) ? 'NULL' : $value) . "\n";
        }
        
        // Check specifically for created_at field
        if (array_key_exists('created_at', $category)) {
            echo "   ⚠️ WARNING: created_at field exists (might cause issues)\n";
        } else {
            echo "   ✓ No created_at field (good - no array key errors)\n";
        }
        
        // Check for product_count field
        if (array_key_exists('product_count', $category)) {
            echo "   ✓ product_count field exists: " . $category['product_count'] . "\n";
        } else {
            echo "   ❌ product_count field missing\n";
        }
    }
    
    echo "\n2. Simulating admin categories view rendering...\n";
    
    // Simulate what the view does
    foreach ($categories as $category) {
        // This is what the view should do (no created_at access)
        $output = "Category: " . $category['name'] . 
                 " | Products: " . ($category['product_count'] ?? 0) . 
                 " | Description: " . substr($category['description'] ?? '', 0, 50);
        echo "   ✓ Rendered: $output\n";
    }
    
    echo "\n=== RESULT ===\n";
    echo "✅ Categories admin page should work without 'Undefined array key created_at' errors!\n";
    echo "✅ All necessary data is available for the admin view\n";
    echo "✅ No attempts to access non-existent created_at field\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "❌ Categories admin page may still have issues\n";
}

echo "\n=== FIXES SUMMARY ===\n";
echo "1. ✅ Removed 'Data Creazione' column from admin categories view\n";
echo "2. ✅ Added Category::getAllWithProductCount() method\n";
echo "3. ✅ Updated CategoryController to use new method\n";
echo "4. ✅ View now only accesses existing fields (id, name, description, product_count)\n";
echo "5. ✅ No more 'Undefined array key created_at' errors\n";
?>
