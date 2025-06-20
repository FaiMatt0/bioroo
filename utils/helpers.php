<?php
// Funzioni helper generali

/**
 * Reindirizza a un altro URL
 */
function redirect($path) {
    header("Location: " . BASE_URL . $path);
    exit;
}

/**
 * Visualizza un messaggio flash
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Ottiene e rimuove un messaggio flash
 */
function getFlashMessage() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Sanitizza input
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Verifica se l'utente è autenticato
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Verifica se l'utente è admin
 */
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

/**
 * Verifica se l'utente è un venditore
 */
function isVendor() {
    return isset($_SESSION['is_vendor']) && $_SESSION['is_vendor'] === true;
}

/**
 * Formatta prezzo
 */
function formatPrice($price) {
    return '€ ' . number_format($price, 2, ',', '.');
}

/**
 * Carica un'immagine
 */
function uploadImage($file, $destination) {
    // Crea la directory se non esiste
    if (!file_exists($destination)) {
        mkdir($destination, 0777, true);
    }
    
    $fileName = time() . '_' . basename($file['name']);
    $targetPath = $destination . '/' . $fileName;
    
    // Sposta il file caricato
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $fileName;
    }
    return false;
}

/**
 * Aggiorna la sessione utente per usare il nuovo formato senza username
 */
function updateUserSession() {
    if (isLoggedIn() && !isset($_SESSION['display_name']) && isset($_SESSION['user_id'])) {
        // Carica il modello User
        require_once MODELS_PATH . '/User.php';
        $userModel = new User();
        
        // Ottieni l'utente dal database
        $user = $userModel->getById($_SESSION['user_id']);
        
        if ($user) {
            // Aggiorna la sessione con il nuovo formato
            $_SESSION['display_name'] = $user['first_name'] . ' ' . $user['last_name'];
            // Rimuovi l'username dalla sessione se presente
            if (isset($_SESSION['username'])) {
                unset($_SESSION['username']);
            }
        }
    }
}