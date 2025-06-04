<?php
class OrderItem {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    /**
     * Ottiene tutti gli elementi di un ordine
     */
    public function getByOrder($orderId) {
        $stmt = $this->conn->prepare("
            SELECT oi.*, p.name, p.image 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.id 
            WHERE oi.order_id = ?
        ");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        
        return $items;
    }
    
    /**
     * Ottiene tutti gli elementi di un ordine (alias per retrocompatibilità)
     */
    public function getByOrderId($orderId) {
        return $this->getByOrder($orderId);
    }
    
    /**
     * Crea un nuovo elemento dell'ordine
     */
    public function create($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iiid",
            $data['order_id'],
            $data['product_id'],
            $data['quantity'],
            $data['price']
        );
        
        return $stmt->execute();
    }
    
    /**
     * Chiude la connessione quando l'oggetto viene distrutto
     */
    public function __destruct() {
        $this->conn->close();
    }
}