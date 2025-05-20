<?php
require_once MODELS_PATH . '/Cart.php';
require_once MODELS_PATH . '/Product.php';

class CartController {
    private $cartModel;
    private $productModel;
    
    public function __construct() {
        $this->cartModel = new Cart();
        $this->productModel = new Product();
    }
    
    /**
     * Mostra il carrello
     */
    public function index() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per visualizzare il carrello.');
            redirect('/login');
        }
        
        $userId = $_SESSION['user_id'];
        $cartItems = $this->cartModel->getByUser($userId);
        
        // Calcola il totale
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        include VIEWS_PATH . '/cart/index.php';
    }
    
    /**
     * Aggiungi un prodotto al carrello
     */
    public function add() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per aggiungere prodotti al carrello.');
            redirect('/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int)$_POST['product_id'];
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            // Validazione
            if (!validatePositiveInt($productId) || !validatePositiveInt($quantity)) {
                setFlashMessage('error', 'Dati del prodotto o della quantità non validi.');
                redirect('/products');
            }
            
            // Verifica se il prodotto esiste
            $product = $this->productModel->getById($productId);
            
            if (!$product) {
                setFlashMessage('error', 'Prodotto non trovato.');
                redirect('/products');
            }
            
            // Verifica disponibilità
            if ($product['stock_quantity'] < $quantity) {
                setFlashMessage('error', 'Quantità richiesta non disponibile in magazzino.');
                redirect('/products/' . $productId);
            }
            
            $userId = $_SESSION['user_id'];
            
            // Controlla se il prodotto è già nel carrello
            $existingItem = $this->cartModel->getByUserAndProduct($userId, $productId);
            
            if ($existingItem) {
                // Aggiorna la quantità
                $newQuantity = $existingItem['quantity'] + $quantity;
                
                // Verifica nuovamente la disponibilità
                if ($product['stock_quantity'] < $newQuantity) {
                    setFlashMessage('error', 'Quantità totale richiesta non disponibile in magazzino.');
                    redirect('/products/' . $productId);
                }
                
                $this->cartModel->updateQuantity($existingItem['id'], $newQuantity);
                setFlashMessage('success', 'Quantità aggiornata nel carrello!');
            } else {
                // Aggiungi nuovo elemento al carrello
                $this->cartModel->add($userId, $productId, $quantity);
                setFlashMessage('success', 'Prodotto aggiunto al carrello!');
            }
            
            redirect('/cart');
        } else {
            redirect('/products');
        }
    }
    
    /**
     * Aggiorna la quantità di un prodotto nel carrello
     */
    public function update() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per aggiornare il carrello.');
            redirect('/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartId = (int)$_POST['cart_id'];
            $quantity = (int)$_POST['quantity'];
            
            // Validazione
            if (!validatePositiveInt($cartId) || !validatePositiveInt($quantity)) {
                setFlashMessage('error', 'Dati del carrello o della quantità non validi.');
                redirect('/cart');
            }
            
            // Verifica che l'elemento del carrello appartenga all'utente
            $cartItem = $this->cartModel->getById($cartId);
            
            if (!$cartItem || $cartItem['user_id'] != $_SESSION['user_id']) {
                setFlashMessage('error', 'Elemento del carrello non trovato.');
                redirect('/cart');
            }
            
            // Verifica disponibilità
            $product = $this->productModel->getById($cartItem['product_id']);
            
            if ($product['stock_quantity'] < $quantity) {
                setFlashMessage('error', 'Quantità richiesta non disponibile in magazzino.');
                redirect('/cart');
            }
            
            // Aggiorna la quantità
            $this->cartModel->updateQuantity($cartId, $quantity);
            setFlashMessage('success', 'Carrello aggiornato!');
            
            redirect('/cart');
        } else {
            redirect('/cart');
        }
    }
    
    /**
     * Rimuovi un prodotto dal carrello
     */
    public function remove() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per rimuovere prodotti dal carrello.');
            redirect('/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cartId = (int)$_POST['cart_id'];
            
            // Validazione
            if (!validatePositiveInt($cartId)) {
                setFlashMessage('error', 'ID carrello non valido.');
                redirect('/cart');
            }
            
            // Verifica che l'elemento del carrello appartenga all'utente
            $cartItem = $this->cartModel->getById($cartId);
            
            if (!$cartItem || $cartItem['user_id'] != $_SESSION['user_id']) {
                setFlashMessage('error', 'Elemento del carrello non trovato.');
                redirect('/cart');
            }
            
            // Rimuovi dal carrello
            $this->cartModel->remove($cartId);
            setFlashMessage('success', 'Prodotto rimosso dal carrello!');
            
            redirect('/cart');
        } else {
            redirect('/cart');
        }
    }
    
    /**
     * Svuota il carrello
     */
    public function clear() {
        if (!isLoggedIn()) {
            setFlashMessage('error', 'Devi effettuare il login per svuotare il carrello.');
            redirect('/login');
        }
        
        $userId = $_SESSION['user_id'];
        
        // Svuota il carrello
        $this->cartModel->clearByUser($userId);
        setFlashMessage('success', 'Carrello svuotato!');
        
        redirect('/cart');
    }
}