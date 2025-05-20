<?php
// links-test.php - Place this in your root directory
// Include the configuration file
require_once 'config/config.php';

// Define all the links we want to test based on your project structure
$links = [
    // Main pages
    'Home' => BASE_URL . '/pages/home.php',
    'About' => BASE_URL . '/pages/  ',
    'Products' => BASE_URL . '/pages/',
    'Sustainability' => BASE_URL . '/pages/',
    'Contact' => BASE_URL . '/pages/',
    
    // Auth
    'Login' => BASE_URL . '/auth/login',
    'Register' => BASE_URL . '/auth/register',
    'Logout' => BASE_URL . '/auth/logout',
    
    // Products
    'Products Category Example' => BASE_URL . '/products/category/1',
    'Product Example' => BASE_URL . '/products/1',
    'Product Search' => BASE_URL . '/products/search?keyword=example',
    
    // Cart
    'Cart' => BASE_URL . '/cart',
    'Add to Cart' => BASE_URL . '/cart/add',
    'Update Cart' => BASE_URL . '/cart/update',
    'Remove from Cart' => BASE_URL . '/cart/remove',
    'Clear Cart' => BASE_URL . '/cart/clear',
    
    // Checkout
    'Checkout' => BASE_URL . '/checkout',
    'Process Order' => BASE_URL . '/order/process',
    'Payment' => BASE_URL . '/payment',
    'Process Payment' => BASE_URL . '/payment/process',
    
    // User profile
    'Profile' => BASE_URL . '/profile',
    'Edit Profile' => BASE_URL . '/profile/edit',
    'Update Profile' => BASE_URL . '/profile/update',
    'Change Password' => BASE_URL . '/profile/change-password',
    
    // Orders
    'My Orders' => BASE_URL . '/orders',
    'Order Details' => BASE_URL . '/orders/1',
    
    // Admin
    'Admin Dashboard' => BASE_URL . '/admin',
    'Admin Products' => BASE_URL . '/admin/products',
    'Admin Create Product' => BASE_URL . '/admin/products/create',
    'Admin Edit Product' => BASE_URL . '/admin/products/edit/1',
    'Admin Orders' => BASE_URL . '/admin/orders',
    'Admin Order Details' => BASE_URL . '/admin/orders/1',
    'Admin Users' => BASE_URL . '/admin/users',
    
    // Direct access to views
    'Direct View - About' => BASE_URL . '/views/pages/about',
    'Direct View - Products' => BASE_URL . '/views/products',
    'Direct View - Contact' => BASE_URL . '/views/pages/contact',
    'Direct View - Login' => BASE_URL . '/views/auth/login',
];

// Define categories to organize links
$categories = [
    'Main Pages' => ['Home', 'About', 'Products', 'Sustainability', 'Contact'],
    'Authentication' => ['Login', 'Register', 'Logout'],
    'Products' => ['Products', 'Products Category Example', 'Product Example', 'Product Search'],
    'Shopping' => ['Cart', 'Add to Cart', 'Update Cart', 'Remove from Cart', 'Clear Cart', 'Checkout', 'Process Order', 'Payment', 'Process Payment'],
    'User Account' => ['Profile', 'Edit Profile', 'Update Profile', 'Change Password', 'My Orders', 'Order Details'],
    'Admin' => ['Admin Dashboard', 'Admin Products', 'Admin Create Product', 'Admin Edit Product', 'Admin Orders', 'Admin Order Details', 'Admin Users'],
    'Direct View Access' => ['Direct View - About', 'Direct View - Products', 'Direct View - Contact', 'Direct View - Login'],
];

// Check if direct file exists
function fileExistsInViews($path) {
    // Remove leading slash if present
    if (substr($path, 0, 1) === '/') {
        $path = substr($path, 1);
    }
    
    $full_path = ROOT_PATH . '/' . $path . '.php';
    return file_exists($full_path);
}

// Display the HTML page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Links Test - Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; }
        .success { color: green; }
        .warning { color: orange; }
        .error { color: red; }
        .links-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Links Test - Marketplace</h1>
        
        <div class="alert alert-info mb-4">
            <h4>BASE_URL Configuration</h4>
            <p>Current BASE_URL is set to: <strong><?= BASE_URL ?></strong></p>
            <p><strong>ROOT_PATH</strong>: <?= ROOT_PATH ?></p>
            <p>Click on any link to test if it works. If you see a 404 error, the link is not correctly configured.</p>
        </div>
        
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Current Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Environment</h6>
                                <ul class="list-group mb-3">
                                    <li class="list-group-item">Server: <?= $_SERVER['SERVER_SOFTWARE'] ?></li>
                                    <li class="list-group-item">PHP Version: <?= phpversion() ?></li>
                                    <li class="list-group-item">Current Directory: <?= getcwd() ?></li>
                                    <li class="list-group-item">Document Root: <?= $_SERVER['DOCUMENT_ROOT'] ?></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Session</h6>
                                <ul class="list-group">
                                    <li class="list-group-item">Session Active: <?= session_status() === PHP_SESSION_ACTIVE ? 'Yes' : 'No' ?></li>
                                    <li class="list-group-item">Logged In: <?= isLoggedIn() ? 'Yes' : 'No' ?></li>
                                    <?php if (isLoggedIn()): ?>
                                        <li class="list-group-item">User: <?= $_SESSION['username'] ?></li>
                                        <li class="list-group-item">Admin: <?= isset($_SESSION['is_admin']) && $_SESSION['is_admin'] ? 'Yes' : 'No' ?></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php foreach ($categories as $category => $linkNames): ?>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?= $category ?></h5>
            </div>
            <div class="card-body">
                <div class="links-grid">
                    <?php foreach ($linkNames as $name): ?>
                        <?php 
                            $url = $links[$name];
                            $urlPath = parse_url($url, PHP_URL_PATH);
                            $isDirectView = strpos($urlPath, '/views/') === 0;
                            $fileExists = false;
                            
                            if ($isDirectView) {
                                $fileExists = fileExistsInViews($urlPath);
                            }
                        ?>
                        <div class="card card-hover">
                            <div class="card-body">
                                <h5 class="card-title"><?= $name ?></h5>
                                <p class="card-text text-truncate">
                                    <small><?= $url ?></small>
                                </p>
                                <?php if ($isDirectView): ?>
                                    <p class="<?= $fileExists ? 'success' : 'error' ?>">
                                        <small>
                                        <?= $fileExists ? 'File exists' : 'File not found' ?>
                                        </small>
                                    </p>
                                <?php endif; ?>
                                <a href="<?= $url ?>" target="_blank" class="btn btn-primary btn-sm">Test Link</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Views Directory Map</h5>
            </div>
            <div class="card-body">
                <p>This shows the structure of your views directory and checks if files exist:</p>
                
                <?php
                // Function to scan directory and create a tree
                function scanDirectory($dir) {
                    $result = [];
                    $files = scandir($dir);
                    
                    foreach ($files as $file) {
                        if ($file === '.' || $file === '..') continue;
                        
                        $path = $dir . '/' . $file;
                        if (is_dir($path)) {
                            $result[$file] = scanDirectory($path);
                        } else {
                            $result[] = $file;
                        }
                    }
                    
                    return $result;
                }
                
                // Function to display directory tree
                function displayTree($tree, $indent = 0, $basePath = '') {
                    foreach ($tree as $key => $value) {
                        if (is_array($value)) {
                            // It's a directory
                            echo str_repeat('&nbsp;', $indent * 4) . '📁 ' . $key . '<br>';
                            displayTree($value, $indent + 1, $basePath . '/' . $key);
                        } else {
                            // It's a file
                            $fullPath = $basePath . '/' . $value;
                            echo str_repeat('&nbsp;', $indent * 4) . '📄 ' . $value . ' ';
                            echo '<small><a href="' . BASE_URL . $fullPath . '" target="_blank">Test</a></small>';
                            echo '<br>';
                        }
                    }
                }
                
                // Display the tree
                if (is_dir(ROOT_PATH . '/views')) {
                    $viewsTree = scanDirectory(ROOT_PATH . '/views');
                    displayTree($viewsTree, 0, '/views');
                } else {
                    echo '<div class="alert alert-danger">Views directory not found at: ' . ROOT_PATH . '/views</div>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>