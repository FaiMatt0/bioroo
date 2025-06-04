<?php
class PageController {
    
    /**
     * Homepage
     */
    public function index() {
        // Carica i prodotti in evidenza
        require_once MODELS_PATH . '/Product.php';
        $productModel = new Product();
        $featuredProducts = $productModel->getFeatured(8);
        
        // Carica le categorie principali
        require_once MODELS_PATH . '/Category.php';
        $categoryModel = new Category();
        $mainCategories = $categoryModel->getMain();
        
        $pageType = 'home';
        include VIEWS_PATH . '/pages/index.php';
    }
    
    /**
     * Pagina storia aziendale
     */
    public function about() {
        $pageType = 'about';
        include VIEWS_PATH . '/pages/about.php';
    }
    
    /**
     * Pagina contatti
     */
    public function contact() {
        $pageType = 'contact';
        include VIEWS_PATH . '/pages/contact.php';
    }
    
    /**
     * Invio form contatti
     */
    public function sendContact() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize($_POST['name']);
            $email = sanitize($_POST['email']);
            $message = sanitize($_POST['message']);
            
            // Validazione
            $errors = [];
            
            if (!validateRequired($name)) {
                $errors['name'] = "Nome richiesto";
            }
            
            if (!validateEmail($email)) {
                $errors['email'] = "Email non valida";
            }
            
            if (!validateRequired($message)) {
                $errors['message'] = "Messaggio richiesto";
            }
            
            if (empty($errors)) {
                // In un'applicazione reale, qui invieresti l'email
                // Per ora, mostriamo solo un messaggio di successo
                
                setFlashMessage('success', 'Messaggio inviato con successo! Ti risponderemo presto.');
                redirect('/contact');
            } else {
                // Salva gli errori in sessione
                $_SESSION['contact_errors'] = $errors;
                $_SESSION['contact_data'] = [
                    'name' => $name,
                    'email' => $email,
                    'message' => $message
                ];
                
                redirect('/contact');
            }
        } else {
            redirect('/contact');
        }
    }
    
    /**
     * Pagina sostenibilità
     */
    public function sustainability() {
        $pageType = 'sustainability';
        include VIEWS_PATH . '/pages/sustainability.php';
    }
    
    /**
     * Pagina work in progress
     */
    public function wip() {
        $pageType = 'wip';
        include VIEWS_PATH . '/pages/wip.php';
    }
}