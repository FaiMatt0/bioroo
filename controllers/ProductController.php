<?php
require_once MODELS_PATH . '/Product.php';
require_once MODELS_PATH . '/Category.php';

class ProductController {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    /**
     * Mostra tutti i prodotti (homepage)
     */
    public function index() {
        $products = $this->productModel->getAll(12); // Limita a 12 prodotti
        $categories = $this->categoryModel->getAll();
        
        include VIEWS_PATH . '/products/index.php';
    }
    
    /**
     * Mostra prodotti per categoria
     */
    public function category($categoryId) {
        $category = $this->categoryModel->getById($categoryId);
        
        if (!$category) {
            // Categoria non trovata
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        $products = $this->productModel->getByCategory($categoryId);
        $categories = $this->categoryModel->getAll();
        
        include VIEWS_PATH . '/products/category.php';
    }
    
    /**
     * Mostra dettagli di un prodotto
     */
    public function show($productId) {
        $product = $this->productModel->getById($productId);
        
        if (!$product) {
            // Prodotto non trovato
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Ottieni prodotti correlati stessa categoria
        $relatedProducts = $this->productModel->getByCategory($product['category_id'], 4);
        
        include VIEWS_PATH . '/products/show.php';
    }
    
    /**
     * Ricerca prodotti
     */
    public function search() {
        $keyword = isset($_GET['keyword']) ? sanitize($_GET['keyword']) : '';
        
        if (empty($keyword)) {
            redirect('/products');
        }
        
        $products = $this->productModel->search($keyword);
        $categories = $this->categoryModel->getAll();
        
        include VIEWS_PATH . '/products/search.php';
    }
    
    /**
     * Mostra form per aggiungere un prodotto (solo venditori)
     */
    public function create() {
        // Verifica se l'utente è un venditore
        if (!isLoggedIn() || !isVendor()) {
            setFlashMessage('error', 'Accesso negato. Solo i venditori possono aggiungere prodotti.');
            redirect('/login');
        }
        
        $categories = $this->categoryModel->getAll();
        
        include VIEWS_PATH . '/products/create.php';
    }
    
    /**
     * Salva un nuovo prodotto
     */
    public function store() {
        // Verifica se l'utente è un venditore
        if (!isLoggedIn() || !isVendor()) {
            setFlashMessage('error', 'Accesso negato. Solo i venditori possono aggiungere prodotti.');
            redirect('/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize($_POST['name']);
            $description = sanitize($_POST['description']);
            $price = (float)$_POST['price'];
            $stockQuantity = (int)$_POST['stock_quantity'];
            $categoryId = (int)$_POST['category_id'];
            
            // Validazione
            $errors = [];
            
            if (!validateRequired($name)) {
                $errors['name'] = "Nome prodotto richiesto";
            }
            
            if (!validatePrice($price)) {
                $errors['price'] = "Prezzo non valido";
            }
            
            if (!validatePositiveInt($stockQuantity)) {
                $errors['stock_quantity'] = "Quantità non valida";
            }
            
            // Gestione upload immagine
            $image = 'default.jpg'; // Immagine predefinita
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = UPLOADS_PATH . '/products/';
                $image = uploadImage($_FILES['image'], $uploadDir);
                
                if (!$image) {
                    $errors['image'] = "Errore durante l'upload dell'immagine";
                }
            }
            
            // Se non ci sono errori, crea il prodotto
            if (empty($errors)) {
                $productData = [
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'stock_quantity' => $stockQuantity,
                    'category_id' => $categoryId,
                    'user_id' => $_SESSION['user_id'],
                    'image' => $image
                ];
                
                $productId = $this->productModel->create($productData);
                
                if ($productId) {
                    setFlashMessage('success', 'Prodotto aggiunto con successo!');
                    redirect('/products/' . $productId);
                } else {
                    $errors['create'] = "Errore durante la creazione del prodotto";
                }
            }
            
            // Se ci sono errori, mostra di nuovo il form
            $categories = $this->categoryModel->getAll();
            include VIEWS_PATH . '/products/create.php';
        } else {
            redirect('/products/create');
        }
    }
    
    /**
     * Mostra form per modificare un prodotto
     */
    public function edit($productId) {
        // Verifica se l'utente è un venditore
        if (!isLoggedIn() || !isVendor()) {
            setFlashMessage('error', 'Accesso negato. Solo i venditori possono modificare prodotti.');
            redirect('/login');
        }
        
        $product = $this->productModel->getById($productId);
        
        if (!$product) {
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Verifica che l'utente sia il proprietario del prodotto
        if ($product['user_id'] != $_SESSION['user_id'] && !isAdmin()) {
            setFlashMessage('error', 'Non sei autorizzato a modificare questo prodotto.');
            redirect('/products');
        }
        
        $categories = $this->categoryModel->getAll();
        
        include VIEWS_PATH . '/products/edit.php';
    }
    
    /**
     * Aggiorna un prodotto
     */
    public function update($productId) {
        // Verifica se l'utente è un venditore
        if (!isLoggedIn() || !isVendor()) {
            setFlashMessage('error', 'Accesso negato. Solo i venditori possono modificare prodotti.');
            redirect('/login');
        }
        
        $product = $this->productModel->getById($productId);
        
        if (!$product) {
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Verifica che l'utente sia il proprietario del prodotto
        if ($product['user_id'] != $_SESSION['user_id'] && !isAdmin()) {
            setFlashMessage('error', 'Non sei autorizzato a modificare questo prodotto.');
            redirect('/products');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize($_POST['name']);
            $description = sanitize($_POST['description']);
            $price = (float)$_POST['price'];
            $stockQuantity = (int)$_POST['stock_quantity'];
            $categoryId = (int)$_POST['category_id'];
            
            // Validazione
            $errors = [];
            
            if (!validateRequired($name)) {
                $errors['name'] = "Nome prodotto richiesto";
            }
            
            if (!validatePrice($price)) {
                $errors['price'] = "Prezzo non valido";
            }
            
            if (!validatePositiveInt($stockQuantity)) {
                $errors['stock_quantity'] = "Quantità non valida";
            }
            
            // Dati da aggiornare
            $productData = [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'stock_quantity' => $stockQuantity,
                'category_id' => $categoryId
            ];
            
            // Gestione upload immagine
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = UPLOADS_PATH . '/products/';
                $image = uploadImage($_FILES['image'], $uploadDir);
                
                if ($image) {
                    $productData['image'] = $image;
                } else {
                    $errors['image'] = "Errore durante l'upload dell'immagine";
                }
            }
            
            // Se non ci sono errori, aggiorna il prodotto
            if (empty($errors)) {
                $updated = $this->productModel->update($productId, $productData);
                
                if ($updated) {
                    setFlashMessage('success', 'Prodotto aggiornato con successo!');
                    redirect('/products/' . $productId);
                } else {
                    $errors['update'] = "Errore durante l'aggiornamento del prodotto";
                }
            }
            
            // Se ci sono errori, mostra di nuovo il form
            $categories = $this->categoryModel->getAll();
            include VIEWS_PATH . '/products/edit.php';
        } else {
            redirect('/products/edit/' . $productId);
        }
    }
    
    /**
     * Elimina un prodotto
     */
    public function delete($productId) {
        // Verifica se l'utente è un venditore
        if (!isLoggedIn() || !isVendor()) {
            setFlashMessage('error', 'Accesso negato. Solo i venditori possono eliminare prodotti.');
            redirect('/login');
        }
        
        $product = $this->productModel->getById($productId);
        
        if (!$product) {
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Verifica che l'utente sia il proprietario del prodotto
        if ($product['user_id'] != $_SESSION['user_id'] && !isAdmin()) {
            setFlashMessage('error', 'Non sei autorizzato a eliminare questo prodotto.');
            redirect('/products');
        }
        
        // Elimina il prodotto
        if ($this->productModel->delete($productId)) {
            setFlashMessage('success', 'Prodotto eliminato con successo!');
        } else {
            setFlashMessage('error', 'Errore durante l\'eliminazione del prodotto.');
        }
        
        redirect('/products');
    }
}