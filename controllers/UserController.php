<?php
require_once MODELS_PATH . '/User.php';
require_once MODELS_PATH . '/Order.php';
require_once MODELS_PATH . '/Product.php';

class UserController {
    private $userModel;
    private $orderModel;
    private $productModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->orderModel = new Order();
        $this->productModel = new Product();
    }
    
    /**
     * Mostra pagina profilo utente
     */    public function profile() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per visualizzare il profilo.');
            redirect('/auth/login');
        }
        
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getById($userId);
        
        // Ottieni gli ultimi ordini dell'utente
        $recentOrders = $this->orderModel->getByUser($userId, 5);
        
        include VIEWS_PATH . '/user/profile.php';
    }
    
    /**
     * Mostra form per modificare il profilo
     */    public function edit() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per modificare il profilo.');
            redirect('/auth/login');
        }
        
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getById($userId);
        
        include VIEWS_PATH . '/user/edit.php';
    }
    
    /**
     * Aggiorna il profilo
     */    public function update() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per aggiornare il profilo.');
            redirect('/auth/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            
            // Dati da aggiornare
            $userData = [
                'first_name' => sanitize($_POST['first_name']),
                'last_name' => sanitize($_POST['last_name']),
                'address' => sanitize($_POST['address']),
                'city' => sanitize($_POST['city']),
                'postal_code' => sanitize($_POST['postal_code']),
                'country' => sanitize($_POST['country']),
                'phone' => sanitize($_POST['phone'])
            ];
            
            // Aggiorna profilo
            if ($this->userModel->updateProfile($userId, $userData)) {
                setFlashMessage('success', 'Profilo aggiornato con successo!');
                redirect('/profile');
            } else {
                setFlashMessage('error', 'Errore durante l\'aggiornamento del profilo.');
                redirect('/profile/edit');
            }
        } else {
            redirect('/profile/edit');
        }
    }
    
    /**
     * Mostra form per cambiare password
     */    public function changePasswordForm() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per cambiare la password.');
            redirect('/auth/login');
        }
        
        include VIEWS_PATH . '/user/change-password.php';
    }
    
    /**
     * Cambia password
     */    public function changePassword() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per cambiare la password.');
            redirect('/auth/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];
            
            // Validazione
            $errors = [];
            
            // Verifica password attuale
            $user = $this->userModel->getById($userId);
            if (!password_verify($currentPassword, $user['password'])) {
                $errors['current_password'] = "Password attuale non corretta";
            }
            
            if (!validatePassword($newPassword)) {
                $errors['new_password'] = "La nuova password deve contenere almeno 8 caratteri";
            }
            
            if ($newPassword !== $confirmPassword) {
                $errors['confirm_password'] = "Le password non coincidono";
            }
            
            // Se non ci sono errori, aggiorna la password
            if (empty($errors)) {
                if ($this->userModel->changePassword($userId, $newPassword)) {
                    setFlashMessage('success', 'Password cambiata con successo!');
                    redirect('/profile');
                } else {
                    setFlashMessage('error', 'Errore durante il cambio della password.');
                    redirect('/profile/change-password');
                }
            } else {
                // Salva gli errori in sessione
                $_SESSION['password_errors'] = $errors;
                redirect('/profile/change-password');
            }
        } else {
            redirect('/profile/change-password');
        }
    }
    
    /**
     * Mostra la dashboard dell'utente
     */    public function dashboard() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per accedere alla dashboard.');
            redirect('/auth/login');
        }
        
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getById($userId);
        
        // Statistiche per l'utente
        $orderCount = $this->orderModel->countByUser($userId);
        
        include VIEWS_PATH . '/user/dashboard.php';
    }
      /**
     * Mostra la dashboard per admin
     */
    public function adminDashboard() {
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono accedere al pannello admin.');
            redirect('/');
        }
        
        // Statistiche per admin
        $userCount = $this->userModel->countUsers();
        $orderCount = $this->orderModel->countOrders();
        $productCount = $this->productModel->countProducts();
        $totalRevenue = $this->orderModel->getTotalRevenue();
        
        // Statistiche resi
        require_once MODELS_PATH . '/ReturnModel.php';
        $returnModel = new ReturnModel();
        $returnCount = $returnModel->countReturns();
        $pendingReturnCount = $returnModel->countByStatus('requested');
        $recentReturns = $returnModel->getAllWithDetails('', 5, 0);
        
        // Ottieni ordini recenti
        $recentOrders = $this->orderModel->getAll(10);
        
        // Ottieni nuovi utenti
        $newUsers = $this->userModel->getRecentUsers(10);
        
        include VIEWS_PATH . '/admin/dashboard.php';
    }
    
    /**
     * Gestione utenti (solo admin)
     */
    public function manageUsers() {
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono gestire gli utenti.');
            redirect('/');
        }
        
        $users = $this->userModel->getAll();
        
        include VIEWS_PATH . '/admin/users/index.php';
    }
    
    /**
     * Cambia ruolo utente (solo admin)
     */
    public function changeRole() {
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono modificare i ruoli degli utenti.');
            redirect('/');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = (int)$_POST['user_id'];
            $isAdmin = isset($_POST['is_admin']) ? 1 : 0;
            
            if ($this->userModel->updateRoles($userId, $isAdmin)) {
                setFlashMessage('success', 'Ruolo utente aggiornato con successo!');
            } else {
                setFlashMessage('error', 'Errore durante l\'aggiornamento del ruolo utente.');
            }
            
            redirect('/admin/users');
        } else {
            redirect('/admin/users');
        }
    }
    
    /**
     * Visualizza dettagli di un utente (solo admin)
     */
    public function viewUser($userId) {
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono visualizzare i dettagli degli utenti.');
            redirect('/');
        }
        
        $user = $this->userModel->getById($userId);
        
        if (!$user) {
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Ottieni statistiche utente
        $userOrders = $this->orderModel->getByUser($userId);
        $orderCount = count($userOrders);
        $totalSpent = 0;
        
        foreach ($userOrders as $order) {
            $totalSpent += $order['total_amount'];
        }
        
        include VIEWS_PATH . '/admin/users/view.php';
    }
    
    /**
     * Visualizza ordini di un utente (solo admin)
     */
    public function userOrders($userId) {
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono visualizzare gli ordini degli utenti.');
            redirect('/');
        }
        
        $user = $this->userModel->getById($userId);
        
        if (!$user) {
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        $orders = $this->orderModel->getByUser($userId);
        
        include VIEWS_PATH . '/admin/users/orders.php';
    }
}