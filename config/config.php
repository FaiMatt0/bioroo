<?php
// Configurazione generale dell'applicazione
session_start();

// URL base del sito
define('BASE_URL', 'http://localhost/marketplace');  // Cambia con il tuo URL

// Directory del progetto
define('ROOT_PATH', dirname(__DIR__));
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('MODELS_PATH', ROOT_PATH . '/models');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// Impostazioni pagamenti (esempio per PayPal)
define('PAYPAL_CLIENT_ID', 'your_client_id');
define('PAYPAL_CLIENT_SECRET', 'your_client_secret');
define('PAYPAL_MODE', 'sandbox'); // sandbox o live

// Configurazione email
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_USER', 'user@example.com');
define('SMTP_PASS', 'your_password');
define('SMTP_PORT', 587);

// Includi file di database e utilities
require_once 'database.php';
require_once ROOT_PATH . '/utils/helpers.php';
require_once ROOT_PATH . '/utils/validator.php';

// Gestione errori
error_reporting(E_ALL);
ini_set('display_errors', 1);