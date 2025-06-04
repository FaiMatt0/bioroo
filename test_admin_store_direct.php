<?php
// Test rapido per verificare se il metodo adminStore viene chiamato
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config/config.php';
require_once 'utils/helpers.php';

session_start();

echo "<h1>Test Routing Admin Products Store</h1>";

// Simula una sessione admin
$_SESSION['user_id'] = 1;
$_SESSION['is_admin'] = true;
$_SESSION['role'] = 'admin';

echo "<h3>Session Status:</h3>";
echo "isLoggedIn(): " . (isLoggedIn() ? 'Yes' : 'No') . "<br>";
echo "isAdmin(): " . (isAdmin() ? 'Yes' : 'No') . "<br>";

// Simula una richiesta POST
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'name' => 'Test Product',
    'description' => 'Test Description',
    'price' => '29.99',
    'stock_quantity' => '10',
    'category_id' => '1',
    'status' => 'active'
];

echo "<h3>POST Data:</h3>";
echo "<pre>" . print_r($_POST, true) . "</pre>";

echo "<h3>Attempting to call ProductController adminStore method...</h3>";

try {
    require_once 'controllers/ProductController.php';
    $controller = new ProductController();
    
    echo "ProductController instantiated successfully.<br>";
    
    // Cattura l'output per vedere se ci sono errori
    ob_start();
    $controller->adminStore();
    $output = ob_get_clean();
    
    echo "<h4>Method execution completed.</h4>";
    if (!empty($output)) {
        echo "<h4>Output captured:</h4>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "<div style='color: red;'>";
    echo "<strong>Exception occurred:</strong><br>";
    echo $e->getMessage() . "<br>";
    echo "<strong>Stack trace:</strong><br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}

echo "<h3>Checking if product was created...</h3>";
try {
    require_once 'config/database.php';
    $conn = getDBConnection();
    $result = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 1");
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo "<strong>Last product in database:</strong><br>";
        echo "<pre>" . print_r($product, true) . "</pre>";
    } else {
        echo "No products found in database.";
    }
} catch (Exception $e) {
    echo "Error checking database: " . $e->getMessage();
}
?>
