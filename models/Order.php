<?php
class Order {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }    /**
     * Ottiene tutti gli ordini
     */
    public function getAll($limit = null) {
        $sql = "
            SELECT o.*, u.email, CONCAT(u.first_name, ' ', u.last_name) as customer_name 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC
        ";
        
        if ($limit !== null) {
            $sql .= " LIMIT ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $limit);
        } else {
            $stmt = $this->conn->prepare($sql);
        }
        
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
    public function getByUser($userId, $limit = null) {
        $sql = "
            SELECT * FROM orders 
            WHERE user_id = ? 
            ORDER BY created_at DESC
        ";
        
        if ($limit !== null) {
            $sql .= " LIMIT ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $userId, $limit);
        } else {
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $userId);
        }
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
     */    public function getById($id) {
        $stmt = $this->conn->prepare("
            SELECT o.*, u.email, CONCAT(u.first_name, ' ', u.last_name) as customer_name 
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
     */    public function getByStatus($status) {
        $stmt = $this->conn->prepare("
            SELECT o.*, u.email, CONCAT(u.first_name, ' ', u.last_name) as customer_name 
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
     * Conta il numero totale di ordini
     */
    public function countOrders() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM orders");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'];
    }
    
    /**
     * Ottiene il totale delle entrate
     */
    public function getTotalRevenue() {
        $stmt = $this->conn->prepare("SELECT SUM(total_amount) as total_revenue FROM orders WHERE status IN ('processing', 'shipped', 'delivered')");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total_revenue'] ?? 0;
    }
    
    /**
     * Conta il numero di ordini per stato
     */
    public function countByStatus($status = null) {
        if ($status) {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM orders WHERE status = ?");
            $stmt->bind_param("s", $status);
        } else {
            $stmt = $this->conn->prepare("
                SELECT status, COUNT(*) as count,
                       (COUNT(*) * 100.0 / (SELECT COUNT(*) FROM orders)) as percentage
                FROM orders 
                GROUP BY status 
                ORDER BY count DESC
            ");
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($status) {
            $row = $result->fetch_assoc();
            return $row['count'];
        } else {
            $stats = [];
            while ($row = $result->fetch_assoc()) {
                $stats[] = $row;
            }
            return $stats;
        }
    }
      /**
     * Ottiene statistiche mensili delle vendite
     */
    public function getMonthlyStats($months = 12) {
        $stmt = $this->conn->prepare("
            SELECT 
                DATE_FORMAT(created_at, '%Y-%m') as month,
                DATE_FORMAT(created_at, '%M %Y') as month_name,
                COUNT(*) as total_orders,
                SUM(total_amount) as total_revenue,
                AVG(total_amount) as average_order
            FROM orders 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? MONTH)
                  AND status IN ('processing', 'shipped', 'delivered')
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month DESC
        ");
        
        if ($stmt === false) {
            error_log("MySQL prepare error in getMonthlyStats: " . $this->conn->error);
            return [];
        }
        
        $stmt->bind_param("i", $months);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $stats = [];
        while ($row = $result->fetch_assoc()) {
            $stats[] = $row;
        }
        
        return $stats;
    }
    
    /**
     * Ottiene i prodotti più venduti
     */
    public function getTopSellingProducts($limit = 10) {        $stmt = $this->conn->prepare("
            SELECT 
                p.id,
                p.name,
                p.price,
                SUM(oi.quantity) as total_sold,
                SUM(oi.quantity * oi.price) as total_revenue
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN orders o ON oi.order_id = o.id
            WHERE o.status IN ('processing', 'shipped', 'delivered')
            GROUP BY p.id, p.name, p.price
            ORDER BY total_sold DESC
            LIMIT ?
        ");
        
        if ($stmt === false) {
            error_log("MySQL prepare error in getTopSellingProducts: " . $this->conn->error);
            return [];
        }
        
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        return $products;
    }
      /**
     * Ottiene ordini recenti per la dashboard
     */
    public function getRecentOrders($limit = 10) {
        $stmt = $this->conn->prepare("
            SELECT o.*, u.email, CONCAT(u.first_name, ' ', u.last_name) as customer_name 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            ORDER BY o.created_at DESC 
            LIMIT ?
        ");
        
        if ($stmt === false) {
            error_log("MySQL prepare error in getRecentOrders: " . $this->conn->error);
            return [];
        }
        
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        
        return $orders;
    }

    /**
     * Chiude la connessione quando l'oggetto viene distrutto
     */
    public function __destruct() {
        $this->conn->close();
    }
}