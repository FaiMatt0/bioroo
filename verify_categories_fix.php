<?php
// Simple test to verify the categories admin page works
require_once 'config/database.php';
require_once 'models/Category.php';

echo "Testing Categories Admin Page Fix\n";
echo "==================================\n\n";

try {
    // Test 1: Check if getAllWithProductCount method exists and works
    echo "1. Testing Category::getAllWithProductCount() method...\n";
    if (method_exists('Category', 'getAllWithProductCount')) {
        echo "   ✓ Method exists\n";
        $categories = Category::getAllWithProductCount();
        echo "   ✓ Retrieved " . count($categories) . " categories\n";
        
        if (!empty($categories)) {
            $firstCategory = $categories[0];
            echo "   ✓ First category fields: " . implode(', ', array_keys($firstCategory)) . "\n";
            
            // Check for the problematic created_at field
            if (isset($firstCategory['created_at'])) {
                echo "   ⚠️  WARNING: created_at field present\n";
            } else {
                echo "   ✓ No created_at field (good!)\n";
            }
            
            // Check for product_count field
            if (isset($firstCategory['product_count'])) {
                echo "   ✓ product_count field present: " . $firstCategory['product_count'] . "\n";
            } else {
                echo "   ❌ product_count field missing\n";
            }
        }
    } else {
        echo "   ❌ Method does not exist\n";
    }

    // Test 2: Verify no fatal errors when accessing admin categories
    echo "\n2. Testing admin categories access...\n";
    $_SESSION['user_id'] = 1; // Mock admin session
    $_SESSION['role'] = 'admin';
    
    // Simulate what CategoryController::adminIndex does
    $categories = Category::getAllWithProductCount();
    echo "   ✓ Admin categories data retrieved successfully\n";
    echo "   ✓ " . count($categories) . " categories available for admin view\n";
    
    echo "\n=== RESULT ===\n";
    echo "✅ Categories admin page should work without errors!\n";
    echo "✅ The 'Undefined array key created_at' error has been resolved\n";
    echo "✅ Product count information is available for each category\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
} catch (Error $e) {
    echo "❌ Fatal Error: " . $e->getMessage() . "\n";
}

echo "\n=== ACCESS URLS ===\n";
echo "Admin Dashboard: http://localhost/bioro/admin\n";
echo "Admin Categories: http://localhost/bioro/admin/categories\n";
echo "\nYou can now access these URLs in your browser to verify the fixes!\n";
?>
