<?php
class Payment {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    /**
     * Ottiene un pagamento per ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM payments WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Ottiene pagamenti per ordine
     */
    public function getByOrder($orderId) {
        $stmt = $this->conn->prepare("SELECT * FROM payments WHERE order_id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $payments = [];
        while ($row = $result->fetch_assoc()) {
            $payments[] = $row;
        }
        
        return $payments;
    }
    
    /**
     * Crea un nuovo pagamento
     */
    public function create($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO payments (order_id, amount, payment_method, transaction_id, status) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "idsss",
            $data['order_id'],
            $data['amount'],
            $data['payment_method'],
            $data['transaction_id'],
            $data['status']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    /**
     * Aggiorna lo stato di un pagamento
     */
    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE payments SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        
        return $stmt->execute();
    }
    
    /**
     * Chiude la connessione quando l'oggetto viene distrutto
     */
    public function __destruct() {
        $this->conn->close();
    }
}