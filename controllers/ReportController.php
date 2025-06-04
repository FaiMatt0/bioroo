<?php
require_once MODELS_PATH . '/Order.php';
require_once MODELS_PATH . '/Product.php';
require_once MODELS_PATH . '/User.php';

class ReportController {
    private $orderModel;
    private $productModel;
    private $userModel;
    
    public function __construct() {
        $this->orderModel = new Order();
        $this->productModel = new Product();
        $this->userModel = new User();
    }
    
    /**
     * Admin: Mostra la pagina dei report
     */
    public function index() {
        // Verifica se l'utente è un admin
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono visualizzare i report.');
            redirect('/');
        }
        
        // Calcola statistiche
        $totalOrders = $this->orderModel->countOrders();
        $totalRevenue = $this->orderModel->getTotalRevenue();
        $totalProducts = $this->productModel->countProducts();
        $totalUsers = $this->userModel->countUsers();
        
        // Ordini per stato
        $ordersByStatus = [
            'pending' => $this->orderModel->countByStatus('pending'),
            'processing' => $this->orderModel->countByStatus('processing'),
            'shipped' => $this->orderModel->countByStatus('shipped'),
            'delivered' => $this->orderModel->countByStatus('delivered'),
            'cancelled' => $this->orderModel->countByStatus('cancelled')
        ];
        
        // Prodotti più venduti (da implementare nel modello se necessario)
        $topProducts = $this->orderModel->getTopSellingProducts(10);
        
        // Ordini recenti
        $recentOrders = $this->orderModel->getAll(20);
        
        include VIEWS_PATH . '/admin/reports/index.php';
    }
    
    /**
     * Admin: Esporta report in CSV
     */
    public function export() {
        // Verifica se l'utente è un admin
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono esportare i report.');
            redirect('/');
        }
        
        // Ottieni tipo di export dalla query string
        $type = $_GET['type'] ?? 'orders';
        
        switch ($type) {
            case 'orders':
                $this->exportOrders();
                break;
            case 'products':
                $this->exportProducts();
                break;
            case 'users':
                $this->exportUsers();
                break;
            default:
                $this->exportOrders();
        }
    }
    
    /**
     * Esporta ordini in CSV
     */
    private function exportOrders() {
        $orders = $this->orderModel->getAll();
        
        // Headers per il download CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="ordini_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Headers CSV
        fputcsv($output, [
            'ID',
            'Cliente',
            'Email',
            'Data',
            'Totale',
            'Stato',
            'Indirizzo'
        ]);
        
        // Dati
        foreach ($orders as $order) {
            fputcsv($output, [
                $order['id'],
                $order['customer_name'],
                $order['email'],
                $order['created_at'],
                $order['total_amount'],
                $order['status'],
                $order['address']
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    /**
     * Esporta prodotti in CSV
     */
    private function exportProducts() {
        $products = $this->productModel->getAll();
        
        // Headers per il download CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="prodotti_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Headers CSV
        fputcsv($output, [
            'ID',
            'Nome',
            'Prezzo',
            'Quantità',
            'Categoria',
            'Stato',
            'Data creazione'
        ]);
        
        // Dati
        foreach ($products as $product) {
            fputcsv($output, [
                $product['id'],
                $product['name'],
                $product['price'],
                $product['stock_quantity'],
                $product['category_name'] ?? 'N/A',
                $product['status'] ?? 'active',
                $product['created_at']
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    /**
     * Esporta utenti in CSV
     */
    private function exportUsers() {
        $users = $this->userModel->getAll();
        
        // Headers per il download CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="utenti_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Headers CSV
        fputcsv($output, [
            'ID',
            'Nome',
            'Cognome',
            'Email',
            'Telefono',
            'Admin',
            'Data registrazione'
        ]);
        
        // Dati
        foreach ($users as $user) {
            fputcsv($output, [
                $user['id'],
                $user['first_name'],
                $user['last_name'],
                $user['email'],
                $user['phone'],
                $user['is_admin'] ? 'Si' : 'No',
                $user['created_at']
            ]);
        }
        
        fclose($output);
        exit();
    }
}
