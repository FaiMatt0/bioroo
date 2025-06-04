<?php
class ReturnModel {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    /**
     * Crea una nuova richiesta di reso
     */
    public function createReturn($data) {
        $returnNumber = $this->generateReturnNumber();
        
        $stmt = $this->conn->prepare("
            INSERT INTO returns (order_id, user_id, return_number, reason, reason_description, total_amount, refund_method) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "iisssds",
            $data['order_id'],
            $data['user_id'],
            $returnNumber,
            $data['reason'],
            $data['reason_description'],
            $data['total_amount'],
            $data['refund_method']
        );
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    /**
     * Aggiunge un prodotto al reso
     */
    public function addReturnItem($returnId, $orderItemId, $productId, $quantity, $price) {
        $stmt = $this->conn->prepare("
            INSERT INTO return_items (return_id, order_item_id, product_id, quantity, price) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("iiiid", $returnId, $orderItemId, $productId, $quantity, $price);
        
        return $stmt->execute();
    }
    
    /**
     * Ottiene tutti i resi
     */
    public function getAllReturns($limit = null) {
        $sql = "
            SELECT r.*, o.id as order_number, u.email, 
                   CONCAT(u.first_name, ' ', u.last_name) as customer_name,
                   COUNT(ri.id) as items_count
            FROM returns r 
            JOIN orders o ON r.order_id = o.id
            JOIN users u ON r.user_id = u.id 
            LEFT JOIN return_items ri ON r.id = ri.return_id
            GROUP BY r.id
            ORDER BY r.created_at DESC
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
        
        $returns = [];
        while ($row = $result->fetch_assoc()) {
            $returns[] = $row;
        }
        
        return $returns;
    }
    
    /**
     * Ottiene i resi di un utente
     */
    public function getReturnsByUser($userId, $limit = null) {
        $sql = "
            SELECT r.*, o.id as order_number,
                   COUNT(ri.id) as items_count
            FROM returns r 
            JOIN orders o ON r.order_id = o.id
            LEFT JOIN return_items ri ON r.id = ri.return_id
            WHERE r.user_id = ? 
            GROUP BY r.id
            ORDER BY r.created_at DESC
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
        
        $returns = [];
        while ($row = $result->fetch_assoc()) {
            $returns[] = $row;
        }
        
        return $returns;
    }
    
    /**
     * Ottiene un reso per ID
     */
    public function getReturnById($id) {
        $stmt = $this->conn->prepare("
            SELECT r.*, o.id as order_number, u.email, 
                   CONCAT(u.first_name, ' ', u.last_name) as customer_name,
                   o.shipping_address, o.shipping_city, o.shipping_postal_code, o.shipping_country
            FROM returns r 
            JOIN orders o ON r.order_id = o.id
            JOIN users u ON r.user_id = u.id 
            WHERE r.id = ?
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
     * Ottiene i prodotti di un reso
     */
    public function getReturnItems($returnId) {
        $stmt = $this->conn->prepare("
            SELECT ri.*, p.name as product_name, p.image as product_image,
                   oi.quantity as original_quantity
            FROM return_items ri
            JOIN products p ON ri.product_id = p.id
            JOIN order_items oi ON ri.order_item_id = oi.id
            WHERE ri.return_id = ?
        ");
        $stmt->bind_param("i", $returnId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        
        return $items;
    }
    
    /**
     * Aggiorna lo stato di un reso
     */
    public function updateStatus($id, $status, $adminNotes = null) {
        $sql = "UPDATE returns SET status = ?";
        $params = [$status];
        $types = "s";
        
        if ($adminNotes !== null) {
            $sql .= ", admin_notes = ?";
            $params[] = $adminNotes;
            $types .= "s";
        }
        
        // Aggiorna timestamp appropriati
        if ($status === 'approved' || $status === 'rejected') {
            $sql .= ", processed_at = NOW()";
        } elseif ($status === 'refunded') {
            $sql .= ", refunded_at = NOW()";
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        $types .= "i";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        
        return $stmt->execute();
    }
    
    /**
     * Aggiorna le condizioni dei prodotti ricevuti
     */
    public function updateItemCondition($returnItemId, $condition, $notes = null) {
        $stmt = $this->conn->prepare("
            UPDATE return_items 
            SET condition_received = ?, notes = ? 
            WHERE id = ?
        ");
        $stmt->bind_param("ssi", $condition, $notes, $returnItemId);
        
        return $stmt->execute();
    }
    
    /**
     * Verifica se un ordine può essere reso
     */
    public function canOrderBeReturned($orderId) {
        // Verifica se l'ordine è stato consegnato da meno di 30 giorni
        $stmt = $this->conn->prepare("
            SELECT id FROM orders 
            WHERE id = ? 
            AND status = 'delivered' 
            AND updated_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return false;
        }
        
        // Verifica se non c'è già un reso per questo ordine
        $stmt = $this->conn->prepare("
            SELECT id FROM returns 
            WHERE order_id = ? 
            AND status NOT IN ('rejected', 'cancelled')
        ");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows === 0;
    }
    
    /**
     * Conta i resi per stato
     */
    public function countByStatus($status = null) {
        if ($status) {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM returns WHERE status = ?");
            $stmt->bind_param("s", $status);
        } else {
            $stmt = $this->conn->prepare("
                SELECT status, COUNT(*) as count,
                       (COUNT(*) * 100.0 / (SELECT COUNT(*) FROM returns)) as percentage
                FROM returns 
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
     * Conta il numero totale di resi
     */
    public function countReturns($status = '') {
        if ($status) {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM returns WHERE status = ?");
            $stmt->bind_param("s", $status);
        } else {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM returns");
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'];
    }
    
    /**
     * Ottiene statistiche sui resi
     */
    public function getReturnStats() {
        $stmt = $this->conn->prepare("
            SELECT 
                COUNT(*) as total_returns,
                SUM(CASE WHEN status = 'requested' THEN 1 ELSE 0 END) as pending_returns,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_returns,
                SUM(CASE WHEN status = 'refunded' THEN 1 ELSE 0 END) as refunded_returns,
                SUM(CASE WHEN status = 'refunded' THEN total_amount ELSE 0 END) as total_refunded_amount,
                AVG(total_amount) as average_return_amount
            FROM returns
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
    
    /**
     * Genera un numero di reso univoco
     */
    private function generateReturnNumber() {
        $prefix = 'RET';
        $timestamp = time();
        $random = mt_rand(100, 999);
        
        return $prefix . $timestamp . $random;
    }
    
    /**
     * Ottiene i resi per ordine
     */
    public function getReturnsByOrder($orderId) {
        $stmt = $this->conn->prepare("
            SELECT r.*, COUNT(ri.id) as items_count
            FROM returns r 
            LEFT JOIN return_items ri ON r.id = ri.return_id
            WHERE r.order_id = ?
            GROUP BY r.id
            ORDER BY r.created_at DESC
        ");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $returns = [];
        while ($row = $result->fetch_assoc()) {
            $returns[] = $row;
        }
        
        return $returns;
    }
    
    /**
     * Aggiorna solo le note amministratore
     */
    public function updateAdminNotes($id, $adminNotes) {
        $stmt = $this->conn->prepare("UPDATE returns SET admin_notes = ? WHERE id = ?");
        $stmt->bind_param("si", $adminNotes, $id);
        
        return $stmt->execute();
    }

    /**
     * Chiude la connessione quando l'oggetto viene distrutto
     */
    public function __destruct() {
        $this->conn->close();
    }

    /**
     * Ottiene tutti i resi con dettagli per admin dashboard
     */
    public function getAllWithDetails($status = '', $limit = 20, $offset = 0) {
        $sql = "
            SELECT r.*, 
                   u.email as customer_email,
                   CONCAT(u.first_name, ' ', u.last_name) as customer_name,
                   COUNT(ri.id) as total_items,
                   r.total_amount as refund_amount
            FROM returns r
            JOIN users u ON r.user_id = u.id
            LEFT JOIN return_items ri ON r.id = ri.return_id
        ";
        
        $params = [];
        $types = "";
        
        if ($status) {
            $sql .= " WHERE r.status = ?";
            $params[] = $status;
            $types .= "s";
        }
        
        $sql .= " GROUP BY r.id ORDER BY r.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= "ii";
        
        $stmt = $this->conn->prepare($sql);
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
          $returns = [];
        while ($row = $result->fetch_assoc()) {
            $returns[] = $row;
        }
        
        return $returns;
    }

    /**
     * Ottiene i resi più recenti
     */
    public function getRecent($limit = 10) {
        $stmt = $this->conn->prepare("
            SELECT r.*, u.first_name, u.last_name, o.id as order_number
            FROM returns r
            JOIN users u ON r.user_id = u.id
            JOIN orders o ON r.order_id = o.id
            ORDER BY r.created_at DESC
            LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $returns = [];
        while ($row = $result->fetch_assoc()) {
            $returns[] = $row;
        }
        
        return $returns;
    }
}
