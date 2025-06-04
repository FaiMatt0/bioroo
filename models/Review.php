<?php
class Review {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
      /**
     * Ottiene tutte le recensioni per un prodotto
     */
    public function getByProduct($productId) {
        $stmt = $this->conn->prepare("
            SELECT r.*, CONCAT(u.first_name, ' ', u.last_name) as reviewer_name 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = ? 
            ORDER BY r.created_at DESC
        ");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        
        return $reviews;
    }
    
    /**
     * Ottiene una recensione per ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Crea una nuova recensione
     */
    public function create($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO reviews (product_id, user_id, rating, comment) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iiis",
            $data['product_id'],
            $data['user_id'],
            $data['rating'],
            $data['comment']
        );
        
        return $stmt->execute();
    }
    
    /**
     * Aggiorna una recensione
     */
    public function update($id, $rating, $comment) {
        $stmt = $this->conn->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE id = ?");
        $stmt->bind_param("isi", $rating, $comment, $id);
        
        return $stmt->execute();
    }
    
    /**
     * Elimina una recensione
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    /**
     * Calcola la valutazione media di un prodotto
     */
    public function getAverageRating($productId) {
        $stmt = $this->conn->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['avg_rating'] ? round($row['avg_rating'], 1) : 0;
    }
    
    /**
     * Chiude la connessione quando l'oggetto viene distrutto
     */
    public function __destruct() {
        $this->conn->close();
    }
}