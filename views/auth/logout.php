<?php
// logout.php - Simple logout script
// Include configuration
require_once 'config/config.php';

// Destroy the session
session_start();
session_destroy();

// Redirect to home page or login page
redirect('/auth/login');
?>