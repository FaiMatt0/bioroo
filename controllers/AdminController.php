<?php
require_once MODELS_PATH . '/Order.php';
require_once MODELS_PATH . '/Product.php';
require_once MODELS_PATH . '/User.php';
require_once MODELS_PATH . '/ReturnModel.php';

class AdminController {
    private $orderModel;
    private $productModel;
    private $userModel;
    private $returnModel;
    
    public function __construct() {
        $this->orderModel = new Order();
        $this->productModel = new Product();
        $this->userModel = new User();
        $this->returnModel = new ReturnModel();
    }
    
    /**
     * Dashboard amministratore
     */
    public function dashboard() {
        // Verifica se l'utente è amministratore
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Devi essere un amministratore.');
            redirect('/auth/login');
        }
        
        // Statistiche per la dashboard
        $userCount = $this->userModel->countUsers();
        $orderCount = $this->orderModel->countOrders();
        $productCount = $this->productModel->countProducts();
        $totalRevenue = $this->orderModel->getTotalRevenue();
        $returnCount = $this->returnModel->countReturns();
        $pendingReturnCount = $this->returnModel->countByStatus('requested');
        
        // Ordini recenti
        $recentOrders = $this->orderModel->getRecentOrders(5);
        
        // Resi recenti
        $recentReturns = $this->returnModel->getRecent(5);
        
        // Statistiche mensili
        $monthlyStats = $this->orderModel->getMonthlyStats();
        
        // Prodotti più venduti
        $topProducts = $this->orderModel->getTopSellingProducts(5);
        
        // Nuovi utenti recenti
        $newUsers = $this->userModel->getRecentUsers(5);
        
        include VIEWS_PATH . '/admin/dashboard.php';
    }
    
    /**
     * Redirige alla dashboard
     */
    public function index() {
        $this->dashboard();
    }
}
?>