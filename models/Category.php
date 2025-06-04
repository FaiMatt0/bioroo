<?php
class Category {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    /**
     * Ottiene tutte le categorie
     */
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM categories ORDER BY name");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        
        return $categories;
    }
    
    /**
     * Ottiene una categoria per ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Crea una nuova categoria
     */
    public function create($name, $description, $parentId = null) {
        $stmt = $this->conn->prepare("INSERT INTO categories (name, description, parent_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $description, $parentId);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    /**
     * Aggiorna una categoria
     */
    public function update($id, $name, $description, $parentId = null) {
        $stmt = $this->conn->prepare("UPDATE categories SET name = ?, description = ?, parent_id = ? WHERE id = ?");
        $stmt->bind_param("ssii", $name, $description, $parentId, $id);
        
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
    
    /**
     * Elimina una categoria
     */
    public function delete($id) {
        // Prima aggiorna i prodotti nella categoria eliminata
        $stmt = $this->conn->prepare("UPDATE products SET category_id = NULL WHERE category_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        // Poi elimina la categoria
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
    
    /**
     * Ottiene le sottocategorie di una categoria
     */
    public function getSubcategories($parentId) {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE parent_id = ? ORDER BY name");
        $stmt->bind_param("i", $parentId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        
        return $categories;
    }
    
    /**
     * Chiude la connessione quando l'oggetto viene distrutto
     */
    public function __destruct() {
        $this->conn->close();
    }

    /**
     * Ottiene le categorie principali (solo quelle di primo livello)
     */
    public function getMain() {
        $stmt = $this->conn->prepare("SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        
        return $categories;
    }

    /**
     * Ottiene tutte le categorie con conteggio prodotti (per admin)
     */
    public function getAllWithProductCount() {
        $stmt = $this->conn->prepare("
            SELECT c.*, COUNT(p.id) as product_count 
            FROM categories c 
            LEFT JOIN products p ON c.id = p.category_id 
            GROUP BY c.id 
            ORDER BY c.name
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $categories = [];
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
        
        return $categories;
    }

    /**
     * Ottiene una categoria per ID con conteggio prodotti
     */
    public function getByIdWithProductCount($id) {
        $stmt = $this->conn->prepare("
            SELECT c.*, COUNT(p.id) as product_count 
            FROM categories c 
            LEFT JOIN products p ON c.id = p.category_id 
            WHERE c.id = ?
            GROUP BY c.id
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
}