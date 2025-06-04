<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting script...\n";
flush();

try {
    echo "Testing basic echo...\n";
    
    if (file_exists('config/database.php')) {
        echo "Config file exists\n";
    } else {
        echo "Config file missing\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Script finished\n";
?>
