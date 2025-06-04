<?php
require_once 'config/config.php';
require_once 'models/Category.php';

echo "Testing categories fix...\n";

$categoryModel = new Category();
$categories = $categoryModel->getAll();

if (!empty($categories)) {
    $categoryId = $categories[0]['id'];
    $category = $categoryModel->getByIdWithProductCount($categoryId);
    
    if ($category && !array_key_exists('created_at', $category)) {
        echo "SUCCESS: Fix is working - no created_at field\n";
    } else {
        echo "ISSUE: created_at field still present\n";
    }
} else {
    echo "No categories found\n";
}
?>
