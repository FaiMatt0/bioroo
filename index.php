<?php
// File principale che gestisce il routing dell'applicazione

// Includi configurazione
require_once 'config/config.php';

// Enable error reporting for debugging - commenta queste righe in produzione
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ottieni l'URL richiesto
$request_uri = $_SERVER['REQUEST_URI'];

// Ottieni percorso base dal BASE_URL
$base_path = parse_url(BASE_URL, PHP_URL_PATH);

// Debug info
// echo "Request URI: $request_uri<br>";
// echo "Base Path: $base_path<br>";

// Rimuovi il percorso base dall'URI
if ($base_path && strpos($request_uri, $base_path) === 0) {
    $request_uri = substr($request_uri, strlen($base_path));
}

// Rimuovi query string e normalizza percorso
$request_uri = strtok($request_uri, '?');
$request_uri = rtrim($request_uri, '/');
if (empty($request_uri)) {
    $request_uri = '/';
}

// Debug final processed URI
// echo "Processed URI: $request_uri<br>";
// exit;

// Routing configuration based on the structure of controllers
$routes = [
    // Main pages
    '/' => ['PageController', 'index'],
    '/pages/about' => ['PageController', 'about'],
    '/pages/contact' => ['PageController', 'contact'],
    '/contact/send' => ['PageController', 'sendContact'],
    '/pages/sustainability' => ['PageController', 'sustainability'],
    '/pages/wip' => ['PageController', 'wip'],
    
    // Authentication
    '/auth/login' => ['AuthController', 'login'],
    '/auth/register' => ['AuthController', 'register'],
    '/auth/logout' => ['AuthController', 'logout'],
    
    // Products
    '/products' => ['ProductController', 'index'],
    '/products/category/(\d+)' => ['ProductController', 'category'],
    '/products/(\d+)' => ['ProductController', 'show'],
    '/products/search' => ['ProductController', 'search'],
    
    // Cart
    '/cart' => ['CartController', 'index'],
    '/cart/add' => ['CartController', 'add'],
    '/cart/update' => ['CartController', 'update'],
    '/cart/remove' => ['CartController', 'remove'],
    '/cart/clear' => ['CartController', 'clear'],
    
    // Checkout and payment
    '/checkout' => ['OrderController', 'checkout'],
    '/order/process' => ['OrderController', 'process'],
    '/payment' => ['PaymentController', 'index'],
    '/payment/process' => ['PaymentController', 'process'],
    
    // User profile
    '/profile' => ['UserController', 'profile'],
    '/profile/edit' => ['UserController', 'edit'],
    '/profile/update' => ['UserController', 'update'],
    '/profile/change-password' => ['UserController', 'changePasswordForm'],
    '/profile/change-password-process' => ['UserController', 'changePassword'],
      // Orders
    '/orders' => ['OrderController', 'myOrders'],
    '/orders/(\d+)' => ['OrderController', 'show'],
    
    // Returns (Resi)
    '/returns' => ['ReturnController', 'myReturns'],
    '/returns/create/(\d+)' => ['ReturnController', 'create'],
    '/returns/store' => ['ReturnController', 'store'],
    '/returns/view/(\d+)' => ['ReturnController', 'view'],    '/returns/cancel/(\d+)' => ['ReturnController', 'cancel'],
    
    // Admin
    '/admin' => ['AdminController', 'dashboard'],
    '/admin/products' => ['ProductController', 'adminIndex'],
    '/admin/products/create' => ['ProductController', 'adminCreate'],
    '/admin/products/store' => ['ProductController', 'adminStore'],
    '/admin/products/edit/(\d+)' => ['ProductController', 'adminEdit'],
    '/admin/products/update/(\d+)' => ['ProductController', 'adminUpdate'],
    '/admin/products/delete/(\d+)' => ['ProductController', 'adminDelete'],
    '/admin/orders' => ['OrderController', 'index'],
    '/admin/orders/(\d+)' => ['OrderController', 'adminShow'],
    '/admin/orders/pending' => ['OrderController', 'pending'],
    '/admin/orders/update-status/(\d+)' => ['OrderController', 'updateStatus'],
    '/admin/orders/invoice/(\d+)' => ['OrderController', 'invoice'],
    '/admin/users' => ['UserController', 'manageUsers'],
    '/admin/users/change-role' => ['UserController', 'changeRole'],
    '/admin/users/view/(\d+)' => ['UserController', 'viewUser'],
    '/admin/users/orders/(\d+)' => ['UserController', 'userOrders'],
    '/admin/categories' => ['CategoryController', 'adminIndex'],
    '/admin/categories/create' => ['CategoryController', 'adminCreate'],
    '/admin/categories/store' => ['CategoryController', 'adminStore'],
    '/admin/categories/edit/(\d+)' => ['CategoryController', 'adminEdit'],    '/admin/categories/update/(\d+)' => ['CategoryController', 'adminUpdate'],
    '/admin/categories/delete/(\d+)' => ['CategoryController', 'adminDelete'],
    '/admin/reports' => ['ReportController', 'index'],
    '/admin/reports/export' => ['ReportController', 'export'],
    
    // Admin Returns
    '/admin/returns' => ['ReturnController', 'adminIndex'],
    '/admin/returns/view/(\d+)' => ['ReturnController', 'adminShow'],
    '/admin/returns/(\d+)' => ['ReturnController', 'adminShow'],
    '/admin/returns/(\d+)/status' => ['ReturnController', 'updateStatusAjax'],
    '/admin/returns/(\d+)/notes' => ['ReturnController', 'updateNotesAjax'],
    '/admin/returns/update-status' => ['ReturnController', 'updateStatus'],
    '/admin/returns/update-conditions' => ['ReturnController', 'updateItemConditions'],
    '/admin/returns/pending' => ['ReturnController', 'getPendingReturns'],
];

// Funzione per gestire il routing
function handleRoute($routes, $uri) {
    // Debug - Mostra l'URI ricevuto
    // echo "Trying to match URI: " . $uri . "<br>";
    
    foreach ($routes as $route => $handler) {
        // Convert route pattern to regex
        $pattern = str_replace('/', '\/', $route);
        $pattern = '#^' . $pattern . '$#';
        
        // Debug - Mostra il pattern
        // echo "Testing pattern: " . $pattern . "<br>";
        
        // Check if the URI matches the pattern
        if (preg_match($pattern, $uri, $matches)) {
            // Debug - Mostra che ha trovato un match
            // echo "Match found for: " . $route . "<br>";
            
            // Remove the first match (the entire URI)
            array_shift($matches);
            
            // Get controller and method
            list($controller, $method) = $handler;
            
            // Load the controller
            $controllerFile = CONTROLLERS_PATH . '/' . $controller . '.php';
            
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                
                // Create controller instance
                $controllerInstance = new $controller();
                
                // Call the method with the parameters extracted from the URI
                call_user_func_array([$controllerInstance, $method], $matches);
                return true;
            } else {
                // Debug if controller file not found
                echo "<div style='color: red; background: #ffeeee; padding: 10px; margin: 10px; border: 1px solid #ff0000;'>";
                echo "Error: Controller file not found: " . $controllerFile . "<br>";
                echo "Make sure the controllers directory exists and contains this file.<br>";
                echo "Current directory: " . getcwd() . "<br>";
                echo "CONTROLLERS_PATH: " . CONTROLLERS_PATH . "<br>";
                echo "</div>";
                return false;
            }
        }
    }
      // No route found
    // echo "No matching route found for URI: " . $uri . "<br>";
    http_response_code(404);
    include VIEWS_PATH . '/errors/404.php';
    return false;
}

// Gestisci la richiesta con le routes
handleRoute($routes, $request_uri);