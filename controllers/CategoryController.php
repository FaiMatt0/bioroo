<?php
require_once MODELS_PATH . '/Category.php';

class CategoryController {
    private $categoryModel;
    
    public function __construct() {
        $this->categoryModel = new Category();
    }
    
    /**
     * Admin: Mostra tutte le categorie
     */    public function adminIndex() {
        // Verifica se l'utente è un admin
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono gestire le categorie.');
            redirect('/');
        }
        
        $categories = $this->categoryModel->getAllWithProductCount();
        
        include VIEWS_PATH . '/admin/categories/index.php';
    }
    
    /**
     * Admin: Mostra form per aggiungere una categoria
     */
    public function adminCreate() {
        // Verifica se l'utente è un admin
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono gestire le categorie.');
            redirect('/');
        }
        
        include VIEWS_PATH . '/admin/categories/create.php';
    }
    
    /**
     * Admin: Salva una nuova categoria
     */
    public function adminStore() {
        // Verifica se l'utente è un admin
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono gestire le categorie.');
            redirect('/');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize($_POST['name']);
            $description = sanitize($_POST['description'] ?? '');
            
            // Validazione
            $errors = [];
            
            if (!validateRequired($name)) {
                $errors['name'] = "Nome categoria richiesto";
            }
              // Se non ci sono errori, crea la categoria
            if (empty($errors)) {
                $categoryId = $this->categoryModel->create($name, $description);
                
                if ($categoryId) {
                    setFlashMessage('success', 'Categoria aggiunta con successo!');
                    redirect('/admin/categories');
                } else {
                    $errors['create'] = "Errore durante la creazione della categoria";
                }
            }
            
            // Se ci sono errori, mostra di nuovo il form
            include VIEWS_PATH . '/admin/categories/create.php';
        } else {
            redirect('/admin/categories/create');
        }
    }
      /**
     * Admin: Mostra form per modificare una categoria
     */
    public function adminEdit($categoryId) {
        // Verifica se l'utente è un admin
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono gestire le categorie.');
            redirect('/');
        }
        
        $category = $this->categoryModel->getByIdWithProductCount($categoryId);
        
        if (!$category) {
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        include VIEWS_PATH . '/admin/categories/edit.php';
    }
    
    /**
     * Admin: Aggiorna una categoria
     */
    public function adminUpdate($categoryId) {
        // Verifica se l'utente è un admin
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono gestire le categorie.');
            redirect('/');
        }
        
        $category = $this->categoryModel->getById($categoryId);
        
        if (!$category) {
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = sanitize($_POST['name']);
            $description = sanitize($_POST['description'] ?? '');
            
            // Validazione
            $errors = [];
            
            if (!validateRequired($name)) {
                $errors['name'] = "Nome categoria richiesto";
            }
              // Se non ci sono errori, aggiorna la categoria
            if (empty($errors)) {
                $updated = $this->categoryModel->update($categoryId, $name, $description);
                
                if ($updated) {
                    setFlashMessage('success', 'Categoria aggiornata con successo!');
                    redirect('/admin/categories');
                } else {
                    $errors['update'] = "Errore durante l'aggiornamento della categoria";
                }
            }
            
            // Se ci sono errori, mostra di nuovo il form
            include VIEWS_PATH . '/admin/categories/edit.php';
        } else {
            redirect('/admin/categories/edit/' . $categoryId);
        }
    }
    
    /**
     * Admin: Elimina una categoria
     */
    public function adminDelete($categoryId) {
        // Verifica se l'utente è un admin
        if (!isLoggedIn() || !isAdmin()) {
            setFlashMessage('error', 'Accesso negato. Solo gli amministratori possono gestire le categorie.');
            redirect('/');
        }
        
        $category = $this->categoryModel->getById($categoryId);
        
        if (!$category) {
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
            return;
        }
        
        // Verifica che non ci siano prodotti associati
        require_once MODELS_PATH . '/Product.php';
        $productModel = new Product();
        $productsInCategory = $productModel->getByCategory($categoryId);
        
        if (!empty($productsInCategory)) {
            setFlashMessage('error', 'Impossibile eliminare la categoria: contiene dei prodotti.');
            redirect('/admin/categories');
        }
        
        // Elimina la categoria
        if ($this->categoryModel->delete($categoryId)) {
            setFlashMessage('success', 'Categoria eliminata con successo!');
        } else {
            setFlashMessage('error', 'Errore durante l\'eliminazione della categoria.');
        }
        
        redirect('/admin/categories');
    }
}
