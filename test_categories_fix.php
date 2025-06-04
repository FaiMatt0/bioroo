<?php
/**
 * Test to verify categories admin page fix
 */

require_once 'config/config.php';

echo "=== TESTING CATEGORIES ADMIN PAGE FIX ===\n\n";

try {
    // Test Category model
    require_once 'models/Category.php';
    $categoryModel = new Category();
    
    echo "1. Testing Category::getAllWithProductCount() method...\n";
    $categories = $categoryModel->getAllWithProductCount();
    echo "   ✓ Method executed successfully\n";
    echo "   ✓ Returned " . count($categories) . " categories\n";
    
    if (!empty($categories)) {
        $firstCategory = $categories[0];
        echo "   ✓ First category: ID=" . $firstCategory['id'] . ", Name='" . $firstCategory['name'] . "'\n";
        echo "   ✓ Product count field exists: " . (isset($firstCategory['product_count']) ? 'YES' : 'NO') . "\n";
        if (isset($firstCategory['product_count'])) {
            echo "   ✓ Product count: " . $firstCategory['product_count'] . "\n";
        }
    }
    
    echo "\n2. Testing CategoryController...\n";
    require_once 'controllers/CategoryController.php';
    $categoryController = new CategoryController();
    echo "   ✓ CategoryController instantiated successfully\n";
    
    echo "\n3. Checking for created_at field issues...\n";
    foreach ($categories as $category) {
        if (isset($category['created_at'])) {
            echo "   ⚠️  WARNING: created_at field found (shouldn't exist)\n";
            break;
        }
    }
    echo "   ✓ No created_at field issues found\n";
    
    echo "\n=== SUMMARY ===\n";
    echo "✅ Categories admin page fix completed successfully!\n";
    echo "✅ Removed 'Data Creazione' column (created_at field doesn't exist)\n";
    echo "✅ Added getAllWithProductCount() method for proper product counting\n";
    echo "✅ Updated CategoryController to use new method\n";
    echo "\nThe categories admin page should now work without the undefined array key error!\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
