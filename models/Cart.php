<?php
class Cart {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    /**
     * Ottiene tutti gli elementi del carrello per un utente
     */
    public function getByUser($userId) {
        $stmt = $this->conn->prepare("
            SELECT c.*, p.name, p.price, p.image 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.user_id = ?
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cartItems = [];
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }
        
        return $cartItems;
    }
    
    /**
     * Ottiene un elemento del carrello per ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM cart WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Ottiene un elemento del carrello per utente e prodotto
     */
    public function getByUserAndProduct($userId, $productId) {
        $stmt = $this->conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Aggiunge un prodotto al carrello
     */
    public function add($userId, $productId, $quantity) {
        $stmt = $this->conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $userId, $productId, $quantity);
        
        return $stmt->execute();
    }
    
    /**
     * Aggiorna la quantità di un elemento del carrello
     */
    public function updateQuantity($id, $quantity) {
        $stmt = $this->conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $id);
        
        return $stmt->execute();
    }
    
    /**
     * Rimuove un elemento dal carrello
     */
    public function remove($id) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    /**
     * Svuota il carrello di un utente
     */
    public function clearByUser($userId) {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        
        return $stmt->execute();
    }
    
    /**
     * Conta il numero di elementi nel carrello di un utente
     */
    public function countItems($userId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id = ?");
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