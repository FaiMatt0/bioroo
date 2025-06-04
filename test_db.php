<?php
require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "Database connection successful!\n";
    
    // Check if returns table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'returns'");
    if ($stmt->rowCount() > 0) {
        echo "Returns table exists\n";
        
        // Check returns table structure
        $stmt = $conn->query("DESCRIBE returns");
        echo "Returns table structure:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- {$row['Field']}: {$row['Type']}\n";
        }
    } else {
        echo "Returns table does not exist\n";
    }
    
    // Check if return_items table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'return_items'");
    if ($stmt->rowCount() > 0) {
        echo "\nReturn_items table exists\n";
        
        // Check return_items table structure
        $stmt = $conn->query("DESCRIBE return_items");
        echo "Return_items table structure:\n";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "- {$row['Field']}: {$row['Type']}\n";
        }
    } else {
        echo "\nReturn_items table does not exist\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
