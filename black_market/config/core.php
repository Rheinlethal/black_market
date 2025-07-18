<?php
session_start();

// Base URL
define('BASE_URL', 'http://localhost/black_market/');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoloader untuk load class
spl_autoload_register(function ($class_name) {
    $directories = array(
        'models/',
        'controllers/',
        'config/'
    );
    
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once($file);
            return;
        }
    }
});

// Helper functions
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect("index.php?controller=auth&action=login");
    }
}
?>