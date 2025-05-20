<?php
class Product {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    /**
     * Ottiene tutti i prodotti
     */
    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT p.*, c.name as category_name, u.username as vendor_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN users u ON p.user_id = u.id 
                WHERE p.status = 'active' 
                ORDER BY p.created_at DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT ?, ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $offset, $limit);
        } else {
            $stmt = $this->conn->prepare($sql);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        return $products;
    }
    
    /**
     * Ottiene prodotti per categoria
     */
    public function getByCategory($categoryId, $limit = null, $offset = 0) {
        $sql = "SELECT p.*, c.name as category_name, u.username as vendor_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                LEFT JOIN users u ON p.user_id = u.id 
                WHERE p.category_id = ? AND p.status = 'active' 
                ORDER BY p.created_at DESC";
        
        if ($limit !== null) {
            $sql .= " LIMIT ?, ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iii", $categoryId, $offset, $limit);
        } else {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $categoryId);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        return $products;
    }
    
    /**
     * Ottiene un prodotto specifico per ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT p.*, c.name as category_name, u.username as vendor_name 
                                      FROM products p 
                                      LEFT JOIN categories c ON p.category_id = c.id 
                                      LEFT JOIN users u ON p.user_id = u.id 
                                      WHERE p.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Ricerca prodotti
     */
    public function search($keyword) {
        $keyword = "%{$keyword}%";
        
        $stmt = $this->conn->prepare("SELECT p.*, c.name as category_name, u.username as vendor_name 
                                      FROM products p 
                                      LEFT JOIN categories c ON p.category_id = c.id 
                                      LEFT JOIN users u ON p.user_id = u.id 
                                      WHERE (p.name LIKE ? OR p.description LIKE ?) 
                                      AND p.status = 'active' 
                                      ORDER BY p.created_at DESC");
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        return $products;
    }
    
    /**
     * Crea un nuovo prodotto
     */
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO products (name, description, price, stock_quantity, category_id, user_id, image) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdiiss", $data['name'], $data['description'], $data['price'], $data['stock_quantity'], $data['category_id'], $data['user_id'], $data['image']);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    /**
     * Aggiorna un prodotto
     */
    public function update($id, $data) {
        $fields = [];
        $types = "";
        $values = [];
        
        // Costruisci la query dinamicamente
        foreach ($data as $key => $value) {
            if (in_array($key, ['name', 'description', 'price', 'stock_quantity', 'category_id', 'status', 'image'])) {
                $fields[] = "$key = ?";
                
                if (in_array($key, ['price'])) {
                    $types .= "d"; // double
                } elseif (in_array($key, ['stock_quantity', 'category_id'])) {
                    $types .= "i"; // integer
                } else {
                    $types .= "s"; // string
                }
                
                $values[] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE products SET " . implode(", ", $fields) . " WHERE id = ?";
        $types .= "i";
        $values[] = $id;
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }
    
    /**
     * Elimina un prodotto
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        return $stmt->execute();
    }
    
    /**
     * Aggiorna la quantità di un prodotto (dopo un acquisto)
     */
    public function updateStock($id, $quantity) {
        $stmt = $this->conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ? AND stock_quantity >= ?");
        $stmt->bind_param("iii", $quantity, $id, $quantity);
        
        return $stmt->execute() && $stmt->affected_rows > 0;
    }
    
    /**
     * Chiude la connessione quando l'oggetto viene distrutto
     */
    public function __destruct() {
        $this->conn->close();
    }

    /**
     * Ottiene i prodotti in evidenza
     */
    public function getFeatured($limit = 8) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.status = 'active' 
                ORDER BY p.created_at DESC 
                LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        return $products;
    }
}