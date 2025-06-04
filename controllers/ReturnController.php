<?php
require_once MODELS_PATH . '/ReturnModel.php';
require_once MODELS_PATH . '/Order.php';
require_once MODELS_PATH . '/OrderItem.php';

class ReturnController {
    private $returnModel;
    private $orderModel;
    private $orderItemModel;
    
    public function __construct() {
        $this->returnModel = new ReturnModel();
        $this->orderModel = new Order();
        $this->orderItemModel = new OrderItem();
    }
    
    /**
     * Mostra i resi dell'utente
     */
    public function myReturns() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per visualizzare i tuoi resi.');
            redirect('/auth/login');
        }
        
        $userId = $_SESSION['user_id'];
        $returns = $this->returnModel->getReturnsByUser($userId);
        
        include VIEWS_PATH . '/returns/my-returns.php';
    }
    
    /**
     * Mostra form per richiedere un reso
     */
    public function create($orderId) {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per richiedere un reso.');
            redirect('/auth/login');
        }
        
        $userId = $_SESSION['user_id'];
        $order = $this->orderModel->getById($orderId);
        
        // Verifica che l'ordine appartenga all'utente
        if (!$order || $order['user_id'] != $userId) {
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Verifica se l'ordine può essere reso
        if (!$this->returnModel->canOrderBeReturned($orderId)) {
            setFlashMessage('error', 'Questo ordine non può essere reso. Verifica che sia stato consegnato da meno di 30 giorni e che non ci sia già una richiesta di reso attiva.');
            redirect('/orders');
        }
        
        // Ottieni gli articoli dell'ordine
        $orderItems = $this->orderItemModel->getByOrderId($orderId);
        
        include VIEWS_PATH . '/returns/create.php';
    }
    
    /**
     * Processa la richiesta di reso
     */
    public function store() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per richiedere un reso.');
            redirect('/auth/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/orders');
        }
        
        $userId = $_SESSION['user_id'];
        $orderId = (int)$_POST['order_id'];
        $order = $this->orderModel->getById($orderId);
        
        // Verifica che l'ordine appartenga all'utente
        if (!$order || $order['user_id'] != $userId) {
            setFlashMessage('error', 'Ordine non valido.');
            redirect('/orders');
        }
        
        // Verifica se l'ordine può essere reso
        if (!$this->returnModel->canOrderBeReturned($orderId)) {
            setFlashMessage('error', 'Questo ordine non può essere reso.');
            redirect('/orders');
        }
        
        // Validazione dei dati
        $reason = sanitize($_POST['reason']);
        $reasonDescription = sanitize($_POST['reason_description']);
        $refundMethod = sanitize($_POST['refund_method']);
        $selectedItems = $_POST['items'] ?? [];
        
        if (empty($selectedItems)) {
            setFlashMessage('error', 'Devi selezionare almeno un prodotto da rendere.');
            redirect('/returns/create/' . $orderId);
        }
        
        // Calcola il totale del reso
        $totalAmount = 0;
        $orderItems = $this->orderItemModel->getByOrderId($orderId);
        $validItems = [];
        
        foreach ($selectedItems as $itemId => $quantity) {
            $quantity = (int)$quantity;
            if ($quantity <= 0) continue;
            
            foreach ($orderItems as $orderItem) {
                if ($orderItem['id'] == $itemId && $quantity <= $orderItem['quantity']) {
                    $validItems[] = [
                        'order_item_id' => $orderItem['id'],
                        'product_id' => $orderItem['product_id'],
                        'quantity' => $quantity,
                        'price' => $orderItem['price']
                    ];
                    $totalAmount += $orderItem['price'] * $quantity;
                    break;
                }
            }
        }
        
        if (empty($validItems)) {
            setFlashMessage('error', 'Nessun prodotto valido selezionato per il reso.');
            redirect('/returns/create/' . $orderId);
        }
        
        // Crea il reso
        $returnData = [
            'order_id' => $orderId,
            'user_id' => $userId,
            'reason' => $reason,
            'reason_description' => $reasonDescription,
            'total_amount' => $totalAmount,
            'refund_method' => $refundMethod
        ];
        
        $returnId = $this->returnModel->createReturn($returnData);
        
        if ($returnId) {
            // Aggiungi gli articoli al reso
            foreach ($validItems as $item) {
                $this->returnModel->addReturnItem(
                    $returnId,
                    $item['order_item_id'],
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                );
            }
            
            setFlashMessage('success', 'Richiesta di reso inviata con successo! Riceverai una conferma via email.');
            redirect('/returns/view/' . $returnId);
        } else {
            setFlashMessage('error', 'Errore durante l\'invio della richiesta di reso.');
            redirect('/returns/create/' . $orderId);
        }
    }
    
    /**
     * Mostra dettagli di un reso
     */
    public function view($returnId) {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per visualizzare i dettagli del reso.');
            redirect('/auth/login');
        }
        
        $returnData = $this->returnModel->getReturnById($returnId);
        
        if (!$returnData) {
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Verifica che il reso appartenga all'utente (se non è admin)
        if (!isAdmin() && $returnData['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        $returnItems = $this->returnModel->getReturnItems($returnId);
        
        include VIEWS_PATH . '/returns/view.php';
    }
    
    /**
     * Annulla una richiesta di reso
     */
    public function cancel($returnId) {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login.');
            redirect('/auth/login');
        }
        
        $returnData = $this->returnModel->getReturnById($returnId);
        
        if (!$returnData) {
            setFlashMessage('error', 'Reso non trovato.');
            redirect('/returns');
        }
        
        // Verifica che il reso appartenga all'utente
        if ($returnData['user_id'] != $_SESSION['user_id']) {
            setFlashMessage('error', 'Non puoi annullare questo reso.');
            redirect('/returns');
        }
        
        // Verifica che il reso possa essere annullato
        if (!in_array($returnData['status'], ['requested', 'approved'])) {
            setFlashMessage('error', 'Questo reso non può essere annullato.');
            redirect('/returns/view/' . $returnId);
        }
        
        if ($this->returnModel->updateStatus($returnId, 'cancelled')) {
            setFlashMessage('success', 'Richiesta di reso annullata con successo.');
        } else {
            setFlashMessage('error', 'Errore durante l\'annullamento del reso.');
        }
        
        redirect('/returns');    }
    
    // SEZIONE ADMIN
    
    /**
     * Dashboard resi per admin
     */
    public function adminIndex() {
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono gestire i resi.');
            redirect('/');
        }
        
        // Get status filter from query string
        $statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
        
        // Pagination parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        // Make returnModel available to the view
        $returnModel = $this->returnModel;
        
        // Get returns based on status filter
        if ($statusFilter) {
            $returns = $this->returnModel->getAllWithDetails($statusFilter, $limit, $offset);
            $totalCount = $this->returnModel->countReturns($statusFilter);
            $pageTitle = 'Gestione Resi - ' . ucfirst($statusFilter);
        } else {
            $returns = $this->returnModel->getAllWithDetails('', $limit, $offset);
            $totalCount = $this->returnModel->countReturns();
            $pageTitle = 'Gestione Resi';
        }
        
        // Get statistics for the dashboard cards
        $totalReturns = $this->returnModel->countReturns();
        $pendingReturns = $this->returnModel->countByStatus('requested');
        $approvedReturns = $this->returnModel->countByStatus('approved');
        $processingReturns = $this->returnModel->countByStatus('received');
        $completedReturns = $this->returnModel->countByStatus('refunded');
        
        $stats = $this->returnModel->getReturnStats();
        $statusCounts = $this->returnModel->countByStatus();
        
        // Pass status to view for form
        $status = $statusFilter;
        
        include VIEWS_PATH . '/admin/returns/index.php';
    }
      /**
     * Visualizza dettagli reso per admin
     */
    public function adminShow($returnId) {
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono gestire i resi.');
            redirect('/');
        }
        
        $return = $this->returnModel->getReturnById($returnId);
        
        if (!$return) {
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        $returnItems = $this->returnModel->getReturnItems($returnId);
        
        include VIEWS_PATH . '/admin/returns/view.php';
    }
    
    /**
     * Aggiorna stato reso (admin)
     */
    public function updateStatus() {
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono gestire i resi.');
            redirect('/');
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/returns');
        }
        
        $returnId = (int)$_POST['return_id'];
        $status = sanitize($_POST['status']);
        $adminNotes = sanitize($_POST['admin_notes']);
        
        if ($this->returnModel->updateStatus($returnId, $status, $adminNotes)) {
            setFlashMessage('success', 'Stato del reso aggiornato con successo.');
        } else {
            setFlashMessage('error', 'Errore durante l\'aggiornamento dello stato del reso.');
        }
        
        redirect('/admin/returns/view/' . $returnId);
    }
    
    /**
     * Aggiorna condizioni prodotti ricevuti (admin)
     */
    public function updateItemConditions() {
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono gestire i resi.');
            redirect('/');
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admin/returns');
        }
        
        $returnId = (int)$_POST['return_id'];
        $itemConditions = $_POST['item_conditions'] ?? [];
        $itemNotes = $_POST['item_notes'] ?? [];
        
        $success = true;
        foreach ($itemConditions as $itemId => $condition) {
            $notes = $itemNotes[$itemId] ?? null;
            if (!$this->returnModel->updateItemCondition($itemId, $condition, $notes)) {
                $success = false;
            }
        }
        
        if ($success) {
            setFlashMessage('success', 'Condizioni dei prodotti aggiornate con successo.');
        } else {
            setFlashMessage('error', 'Errore durante l\'aggiornamento delle condizioni dei prodotti.');
        }
        
        redirect('/admin/returns/view/' . $returnId);
    }
    
    /**
     * Aggiorna stato reso via AJAX (admin)
     */
    public function updateStatusAjax($returnId) {
        if (!isLoggedIn() || !isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accesso negato']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Metodo non consentito']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $status = $input['status'] ?? '';
        
        $validStatuses = ['approved', 'rejected', 'received', 'refunded'];
        if (!in_array($status, $validStatuses)) {
            echo json_encode(['success' => false, 'message' => 'Stato non valido']);
            return;
        }
        
        if ($this->returnModel->updateStatus($returnId, $status)) {
            echo json_encode(['success' => true, 'message' => 'Stato aggiornato con successo']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Errore durante l\'aggiornamento dello stato']);
        }
    }
    
    /**
     * Aggiorna note admin via AJAX
     */
    public function updateNotesAjax($returnId) {
        if (!isLoggedIn() || !isAdmin()) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accesso negato']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Metodo non consentito']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $notes = $input['notes'] ?? '';
        
        if ($this->returnModel->updateAdminNotes($returnId, $notes)) {
            echo json_encode(['success' => true, 'message' => 'Note salvate con successo']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Errore durante il salvataggio delle note']);
        }
    }

    /**
     * Ottieni resi in attesa (AJAX)
     */
    public function getPendingReturns() {
        if (!isLoggedIn() || !isAdmin()) {
            http_response_code(403);
            echo json_encode(['error' => 'Accesso negato']);
            return;
        }
        
        $pendingReturns = $this->returnModel->getAllReturns();
        $pending = array_filter($pendingReturns, function($return) {
            return $return['status'] === 'requested';
        });
        
        header('Content-Type: application/json');
        echo json_encode(array_values($pending));
    }
}
