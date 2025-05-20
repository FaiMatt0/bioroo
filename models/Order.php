<?php
class Order {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    /**
     * Ottiene tutti gli ordini
     */
    public function getAll() {
        $stmt = $this->conn->prepare("
            SELECT o.*, u.username, u.email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        
        return $orders;
    }
    
    /**
     * Ottiene gli ordini di un utente
     */
    public function getByUser($userId) {
        $stmt = $this->conn->prepare("
            SELECT * FROM orders 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        
        return $orders;
    }
    
    /**
     * Ottiene un ordine per ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("
            SELECT o.*, u.username, u.email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.id = ?
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Crea un nuovo ordine
     */
    public function create($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO orders (user_id, total_amount, shipping_address, shipping_city, shipping_postal_code, shipping_country) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "idssss",
            $data['user_id'],
            $data['total_amount'],
            $data['shipping_address'],
            $data['shipping_city'],
            $data['shipping_postal_code'],
            $data['shipping_country']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    /**
     * Aggiorna lo stato di un ordine
     */
    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        
        return $stmt->execute();
    }
    
    /**
     * Aggiorna l'ID del pagamento associato all'ordine
     */
    public function updatePayment($id, $paymentId) {
        $stmt = $this->conn->prepare("UPDATE orders SET payment_id = ? WHERE id = ?");
        $stmt->bind_param("ii", $paymentId, $id);
        
        return $stmt->execute();
    }
    
    /**
     * Ottiene gli ordini con un determinato stato
     */
    public function getByStatus($status) {
        $stmt = $this->conn->prepare("
            SELECT o.*, u.username, u.email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.status = ? 
            ORDER BY o.created_at DESC
        ");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        
        return $orders;
    }
    
    /**
     * Conta il numero di ordini per utente
     */
    public function countByUser($userId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM orders WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'];
    }
    
    /**
     * Chiude la connessione quando l'oggetto viene distrutto
     */
    public function __destruct() {
        $this->conn->close();
    }
}