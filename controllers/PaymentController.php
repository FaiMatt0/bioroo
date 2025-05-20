<?php
require_once MODELS_PATH . '/Payment.php';
require_once MODELS_PATH . '/Order.php';

class PaymentController {
    private $paymentModel;
    private $orderModel;
    
    public function __construct() {
        $this->paymentModel = new Payment();
        $this->orderModel = new Order();
    }
    
    /**
     * Mostra pagina di pagamento
     */
    public function index() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per procedere al pagamento.');
            redirect('/login');
        }
        
        // Verifica se c'è un ordine in attesa
        if (!isset($_SESSION['pending_order_id'])) {
            setFlashMessage('error', 'Nessun ordine in attesa di pagamento.');
            redirect('/orders');
        }
        
        $orderId = $_SESSION['pending_order_id'];
        $order = $this->orderModel->getById($orderId);
        
        // Verifica che l'ordine esista e appartenga all'utente corrente
        if (!$order || $order['user_id'] != $_SESSION['user_id']) {
            setFlashMessage('error', 'Ordine non trovato o accesso negato.');
            redirect('/orders');
        }
        
        include VIEWS_PATH . '/payment/index.php';
    }
    
    /**
     * Elabora il pagamento
     */
    public function process() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per procedere al pagamento.');
            redirect('/login');
        }
        
        // Verifica se c'è un ordine in attesa
        if (!isset($_SESSION['pending_order_id'])) {
            setFlashMessage('error', 'Nessun ordine in attesa di pagamento.');
            redirect('/orders');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_SESSION['pending_order_id'];
            $order = $this->orderModel->getById($orderId);
            
            // Verifica che l'ordine esista e appartenga all'utente corrente
            if (!$order || $order['user_id'] != $_SESSION['user_id']) {
                setFlashMessage('error', 'Ordine non trovato o accesso negato.');
                redirect('/orders');
            }
            
            $paymentMethod = sanitize($_POST['payment_method']);
            
            // Validazione
            if (!in_array($paymentMethod, ['credit_card', 'paypal', 'bank_transfer'])) {
                setFlashMessage('error', 'Metodo di pagamento non valido.');
                redirect('/payment');
            }
            
            // Qui implementeresti l'integrazione reale con il gateway di pagamento
            // Per semplicità, simuliamo un pagamento riuscito
            
            // Genera un ID transazione simulato
            $transactionId = 'TX' . time() . rand(1000, 9999);
            
            // Dati pagamento
            $paymentData = [
                'order_id' => $orderId,
                'amount' => $order['total_amount'],
                'payment_method' => $paymentMethod,
                'transaction_id' => $transactionId,
                'status' => 'completed' // In un'applicazione reale, potrebbe essere 'pending' fino a conferma
            ];
            
            // Registra il pagamento
            $paymentId = $this->paymentModel->create($paymentData);
            
            if ($paymentId) {
                // Aggiorna l'ordine con l'ID del pagamento
                $this->orderModel->updatePayment($orderId, $paymentId);
                
                // Cambia lo stato dell'ordine in 'processing'
                $this->orderModel->updateStatus($orderId, 'processing');
                
                // Rimuovi l'ordine in attesa dalla sessione
                unset($_SESSION['pending_order_id']);
                
                setFlashMessage('success', 'Pagamento completato con successo! Grazie per il tuo ordine.');
                redirect('/orders/' . $orderId);
            } else {
                setFlashMessage('error', 'Errore durante l\'elaborazione del pagamento. Riprova più tardi.');
                redirect('/payment');
            }
        } else {
            redirect('/payment');
        }
    }
    
    /**
     * Callback per pagamenti PayPal (esempio)
     */
    public function paypalCallback() {
        // Qui implementeresti la gestione delle notifiche IPN di PayPal
        // Per semplicità, non implementato in questo esempio
        
        // Ricevi i dati da PayPal
        $data = $_POST;
        
        // Verifica l'autenticità della richiesta
        // ...
        
        // Aggiorna lo stato del pagamento
        // ...
        
        // Restituisci 200 OK a PayPal
        http_response_code(200);
        exit;
    }
    
    /**
     * Visualizza la ricevuta di pagamento
     */
    public function receipt($paymentId) {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per visualizzare le ricevute di pagamento.');
            redirect('/login');
        }
        
        $payment = $this->paymentModel->getById($paymentId);
        
        if (!$payment) {
            setFlashMessage('error', 'Pagamento non trovato.');
            redirect('/orders');
        }
        
        $order = $this->orderModel->getById($payment['order_id']);
        
        // Verifica che l'ordine appartenga all'utente corrente o che l'utente sia admin
        if (!$order || ($order['user_id'] != $_SESSION['user_id'] && !isAdmin())) {
            setFlashMessage('error', 'Accesso negato.');
            redirect('/orders');
        }
        
        include VIEWS_PATH . '/payment/receipt.php';
    }
}