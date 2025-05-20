<?php
class User {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    /**
     * Registra un nuovo utente
     */
    public function register($username, $email, $password, $firstName, $lastName) {
        // Hash della password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $hashedPassword, $firstName, $lastName);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id;
        }
        
        return false;
    }
    
    /**
     * Autenticazione utente
     */
    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT id, username, email, password, is_admin, is_vendor FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                // Password corretta, crea sessione
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['is_admin'] = (bool)$user['is_admin'];
                $_SESSION['is_vendor'] = (bool)$user['is_vendor'];
                
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Ottiene informazioni utente per ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT id, username, email, first_name, last_name, address, city, postal_code, country, phone, is_admin, is_vendor FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return false;
    }
    
    /**
     * Aggiorna profilo utente
     */
    public function updateProfile($id, $data) {
        $fields = [];
        $types = "";
        $values = [];
        
        // Costruisci la query dinamicamente
        foreach ($data as $key => $value) {
            if (in_array($key, ['first_name', 'last_name', 'address', 'city', 'postal_code', 'country', 'phone'])) {
                $fields[] = "$key = ?";
                $types .= "s";
                $values[] = $value;
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = ?";
        $types .= "i";
        $values[] = $id;
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }
    
    /**
     * Cambia password
     */
    public function changePassword($id, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashedPassword, $id);
        
        return $stmt->execute();
    }
    
    /**
     * Chiude la connessione quando l'oggetto viene distrutto
     */
    public function __destruct() {
        $this->conn->close();
    }
}