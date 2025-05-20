<?php
// File principale che gestisce il routing dell'applicazione

// Includi configurazione
require_once 'config/config.php';

// Ottieni l'URL richiesto
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = parse_url(BASE_URL, PHP_URL_PATH);

// Rimuovi il percorso base dall'URI
if ($base_path && strpos($request_uri, $base_path) === 0) {
    $request_uri = substr($request_uri, strlen($base_path));
}

// Rimuovi parametri di query
$request_uri = parse_url($request_uri, PHP_URL_PATH);

// Routing semplice basato sull'URL
$routes = [
    // Pagine principali
    '/' => ['PageController', 'index'],
    '/about' => ['PageController', 'about'],
    '/contact' => ['PageController', 'contact'],
    '/contact/send' => ['PageController', 'sendContact'],
    '/sustainability' => ['PageController', 'sustainability'],
    '/wip' => ['PageController', 'wip'],
    
    // Autenticazione
    '/login' => ['AuthController', 'login'],
    '/register' => ['AuthController', 'register'],
    '/logout' => ['AuthController', 'logout'],
    
    // Prodotti
    '/products' => ['ProductController', 'index'],
    '/products/category/(\d+)' => ['ProductController', 'category'],
    '/products/(\d+)' => ['ProductController', 'show'],
    '/products/search' => ['ProductController', 'search'],
    
    // Carrello
    '/cart' => ['CartController', 'index'],
    '/cart/add' => ['CartController', 'add'],
    '/cart/update' => ['CartController', 'update'],
    '/cart/remove' => ['CartController', 'remove'],
    '/cart/clear' => ['CartController', 'clear'],
    
    // Checkout e pagamento
    '/checkout' => ['OrderController', 'checkout'],
    '/order/process' => ['OrderController', 'process'],
    '/payment' => ['PaymentController', 'index'],
    '/payment/process' => ['PaymentController', 'process'],
    
    // Profilo utente
    '/profile' => ['UserController', 'profile'],
    '/profile/edit' => ['UserController', 'edit'],
    '/profile/update' => ['UserController', 'update'],
    '/profile/change-password' => ['UserController', 'changePasswordForm'],
    '/profile/change-password' => ['UserController', 'changePassword'],
    
    // Ordini
    '/orders' => ['OrderController', 'myOrders'],
    '/orders/(\d+)' => ['OrderController', 'show'],
    
    // Admin
    '/admin' => ['UserController', 'adminDashboard'],
    '/admin/products' => ['ProductController', 'adminIndex'],
    '/admin/products/create' => ['ProductController', 'adminCreate'],
    '/admin/products/store' => ['ProductController', 'adminStore'],
    '/admin/products/edit/(\d+)' => ['ProductController', 'adminEdit'],
    '/admin/products/update/(\d+)' => ['ProductController', 'adminUpdate'],
    '/admin/products/delete/(\d+)' => ['ProductController', 'adminDelete'],
    '/admin/orders' => ['OrderController', 'index'],
    '/admin/orders/(\d+)' => ['OrderController', 'adminShow'],
    '/admin/orders/update-status/(\d+)' => ['OrderController', 'updateStatus'],
    '/admin/users' => ['UserController', 'manageUsers'],
    '/admin/users/change-role' => ['UserController', 'changeRole'],
];

// Funzione per gestire il routing
function handleRoute($routes, $uri) {
    foreach ($routes as $route => $handler) {
        // Converti il percorso del route in un pattern regex
        $pattern = '#^' . $route . '$#';
        
        // Sostituisci i placeholder con regex
        $pattern = str_replace('/', '\/', $pattern);
        
        // Controlla se l'URI corrisponde al pattern
        if (preg_match($pattern, $uri, $matches)) {
            // Rimuovi il primo match (l'intero URI)
            array_shift($matches);
            
            // Ottieni controller e metodo
            list($controller, $method) = $handler;
            
            // Carica il controller
            $controllerFile = CONTROLLERS_PATH . '/' . $controller . '.php';
            
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                
                // Crea istanza del controller
                $controllerInstance = new $controller();
                
                // Chiama il metodo con i parametri estratti dall'URI
                call_user_func_array([$controllerInstance, $method], $matches);
                return true;
            }
        }
    }
    
    // Nessun route trovato
    http_response_code(404);
    include VIEWS_PATH . '/errors/404.php';
    return false;
}

// Gestisci la richiesta
handleRoute($routes, $request_uri);