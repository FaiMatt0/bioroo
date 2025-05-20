<?php
require_once MODELS_PATH . '/User.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Mostra pagina di login
     */
    public function login() {
        // Se già loggato, reindirizza alla home
        if (isLoggedIn()) {
            redirect('/');
        }
        
        // Processa form di login
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];
            
            // Validazione
            $errors = [];
            
            if (!validateEmail($email)) {
                $errors['email'] = "Email non valida";
            }
            
            if (!validateRequired($password)) {
                $errors['password'] = "Password richiesta";
            }
            
            // Se non ci sono errori, tenta il login
            if (empty($errors)) {
                if ($this->userModel->login($email, $password)) {
                    setFlashMessage('success', 'Login effettuato con successo!');
                    redirect('/');
                } else {
                    $errors['login'] = "Email o password non validi";
                }
            }
            
            // Se ci sono errori, passa alla view
            include VIEWS_PATH . '/auth/login.php';
        } else {
            // Mostra form di login
            include VIEWS_PATH . '/auth/login.php';
        }
    }
    
    /**
     * Mostra pagina di registrazione
     */
    public function register() {
        // Se già loggato, reindirizza alla home
        if (isLoggedIn()) {
            redirect('/');
        }
        
        // Processa form di registrazione
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitize($_POST['username']);
            $email = sanitize($_POST['email']);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
            $firstName = sanitize($_POST['first_name']);
            $lastName = sanitize($_POST['last_name']);
            
            // Validazione
            $errors = [];
            
            if (!validateRequired($username)) {
                $errors['username'] = "Username richiesto";
            }
            
            if (!validateEmail($email)) {
                $errors['email'] = "Email non valida";
            }
            
            if (!validatePassword($password)) {
                $errors['password'] = "La password deve contenere almeno 8 caratteri";
            }
            
            if ($password !== $confirmPassword) {
                $errors['confirm_password'] = "Le password non coincidono";
            }
            
            // Se non ci sono errori, registra l'utente
            if (empty($errors)) {
                $userId = $this->userModel->register($username, $email, $password, $firstName, $lastName);
                
                if ($userId) {
                    // Effettua login automatico
                    $this->userModel->login($email, $password);
                    
                    setFlashMessage('success', 'Registrazione completata con successo!');
                    redirect('/');
                } else {
                    $errors['register'] = "Errore durante la registrazione. Username o email già esistenti.";
                }
            }
            
            // Se ci sono errori, passa alla view
            include VIEWS_PATH . '/auth/register.php';
        } else {
            // Mostra form di registrazione
            include VIEWS_PATH . '/auth/register.php';
        }
    }
    
    /**
     * Logout
     */
    public function logout() {
        // Distruggi la sessione
        session_destroy();
        
        // Reindirizza al login
        redirect('/login');
    }
}