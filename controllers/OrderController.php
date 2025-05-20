<?php
require_once MODELS_PATH . '/Order.php';
require_once MODELS_PATH . '/OrderItem.php';
require_once MODELS_PATH . '/Cart.php';
require_once MODELS_PATH . '/Product.php';
require_once MODELS_PATH . '/User.php';

class OrderController {
    private $orderModel;
    private $orderItemModel;
    private $cartModel;
    private $productModel;
    private $userModel;
    
    public function __construct() {
        $this->orderModel = new Order();
        $this->orderItemModel = new OrderItem();
        $this->cartModel = new Cart();
        $this->productModel = new Product();
        $this->userModel = new User();
    }
    
    /**
     * Mostra la pagina di checkout
     */
    public function checkout() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per procedere al checkout.');
            redirect('/login');
        }
        
        $userId = $_SESSION['user_id'];
        $cartItems = $this->cartModel->getByUser($userId);
        
        // Verifica se il carrello è vuoto
        if (empty($cartItems)) {
            setFlashMessage('error', 'Il tuo carrello è vuoto. Aggiungi prodotti prima di procedere al checkout.');
            redirect('/products');
        }
        
        // Ottieni informazioni utente
        $user = $this->userModel->getById($userId);
        
        // Calcola il totale
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        include VIEWS_PATH . '/checkout/index.php';
    }
    
    /**
     * Elabora l'ordine
     */
    public function process() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per procedere all\'ordine.');
            redirect('/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id'];
            $cartItems = $this->cartModel->getByUser($userId);
            
            // Verifica se il carrello è vuoto
            if (empty($cartItems)) {
                setFlashMessage('error', 'Il tuo carrello è vuoto. Aggiungi prodotti prima di procedere all\'ordine.');
                redirect('/products');
            }
            
            // Raccogli dati di spedizione
            $shippingAddress = sanitize($_POST['address']);
            $shippingCity = sanitize($_POST['city']);
            $shippingPostalCode = sanitize($_POST['postal_code']);
            $shippingCountry = sanitize($_POST['country']);
            
            // Validazione
            $errors = [];
            
            if (!validateRequired($shippingAddress)) {
                $errors['address'] = "Indirizzo richiesto";
            }
            
            if (!validateRequired($shippingCity)) {
                $errors['city'] = "Città richiesta";
            }
            
            if (!validateRequired($shippingPostalCode)) {
                $errors['postal_code'] = "Codice postale richiesto";
            }
            
            if (!validateRequired($shippingCountry)) {
                $errors['country'] = "Paese richiesto";
            }
            
            // Verifica disponibilità prodotti
            foreach ($cartItems as $item) {
                $product = $this->productModel->getById($item['product_id']);
                
                if ($product['stock_quantity'] < $item['quantity']) {
                    $errors['stock'] = "Quantità richiesta per '{$product['name']}' non disponibile in magazzino.";
                    break;
                }
            }
            
            // Calcola il totale
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            
            // Se non ci sono errori, crea l'ordine
            if (empty($errors)) {
                // Dati dell'ordine
                $orderData = [
                    'user_id' => $userId,
                    'total_amount' => $total,
                    'shipping_address' => $shippingAddress,
                    'shipping_city' => $shippingCity,
                    'shipping_postal_code' => $shippingPostalCode,
                    'shipping_country' => $shippingCountry
                ];
                
                // Crea l'ordine
                $orderId = $this->orderModel->create($orderData);
                
                if ($orderId) {
                    // Aggiungi elementi dell'ordine
                    foreach ($cartItems as $item) {
                        $orderItemData = [
                            'order_id' => $orderId,
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price']
                        ];
                        
                        $this->orderItemModel->create($orderItemData);
                        
                        // Aggiorna la quantità in magazzino
                        $this->productModel->updateStock($item['product_id'], $item['quantity']);
                    }
                    
                    // Svuota il carrello
                    $this->cartModel->clearByUser($userId);
                    
                    // Salva l'ID dell'ordine in sessione per il pagamento
                    $_SESSION['pending_order_id'] = $orderId;
                    
                    setFlashMessage('success', 'Ordine creato con successo! Procedi al pagamento.');
                    redirect('/payment');
                } else {
                    $errors['order'] = "Errore durante la creazione dell'ordine.";
                }
            }
            
            // Se ci sono errori, torna alla pagina di checkout
            if (!empty($errors)) {
                // Salva gli errori in sessione
                $_SESSION['checkout_errors'] = $errors;
                redirect('/checkout');
            }
        } else {
            redirect('/checkout');
        }
    }
    
    /**
     * Visualizza gli ordini dell'utente
     */
    public function myOrders() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per visualizzare i tuoi ordini.');
            redirect('/login');
        }
        
        $userId = $_SESSION['user_id'];
        $orders = $this->orderModel->getByUser($userId);
        
        include VIEWS_PATH . '/orders/my-orders.php';
    }
    
    /**
     * Visualizza i dettagli di un ordine
     */
    public function show($orderId) {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per visualizzare i dettagli dell\'ordine.');
            redirect('/login');
        }
        
        $userId = $_SESSION['user_id'];
        $order = $this->orderModel->getById($orderId);
        
        // Verifica che l'ordine appartenga all'utente corrente o che l'utente sia admin
        if (!$order || ($order['user_id'] != $userId && !isAdmin())) {
            setFlashMessage('error', 'Ordine non trovato o accesso negato.');
            redirect('/orders');
        }
        
        $orderItems = $this->orderItemModel->getByOrder($orderId);
        
        include VIEWS_PATH . '/orders/show.php';
    }
    
    /**
     * Mostra tutti gli ordini (solo admin)
     */
    public function index() {
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono visualizzare tutti gli ordini.');
            redirect('/');
        }
        
        $orders = $this->orderModel->getAll();
        
        include VIEWS_PATH . '/admin/orders/index.php';
    }
    
    /**
     * Aggiorna lo stato di un ordine (solo admin)
     */
    public function updateStatus($orderId) {
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono aggiornare lo stato degli ordini.');
            redirect('/');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = sanitize($_POST['status']);
            
            // Validazione
            if (!in_array($status, ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])) {
                setFlashMessage('error', 'Stato dell\'ordine non valido.');
                redirect('/admin/orders');
            }
            
            // Aggiorna lo stato
            if ($this->orderModel->updateStatus($orderId, $status)) {
                setFlashMessage('success', 'Stato dell\'ordine aggiornato con successo!');
            } else {
                setFlashMessage('error', 'Errore durante l\'aggiornamento dello stato dell\'ordine.');
            }
            
            redirect('/admin/orders');
        } else {
            redirect('/admin/orders');
        }
    }
}