<?php


// Konfigurasi dasar
require_once 'config/core.php';

// Ambil controller dan action dari parameter GET
$controller = $_GET['controller'] ?? 'home';
$action     = $_GET['action'] ?? 'index';

// Daftar controller yang diizinkan (whitelist)
$allowed_controllers = ['home', 'auth', 'order', 'item', 'chat', 'search', 'admin', 'contract'];

// Cek apakah controller valid
if (!in_array($controller, $allowed_controllers)) {
    http_response_code(404);
    echo "❌ Controller tidak dikenali: <strong>$controller</strong>";
    exit;
}

// Nama class controller, contoh: AdminController
$controller_class = ucfirst($controller) . 'Controller';
$controller_file  = "controllers/{$controller_class}.php";

// Cek file controller ada?
if (!file_exists($controller_file)) {
    http_response_code(404);
    echo "❌ File controller tidak ditemukan: <code>$controller_file</code>";
    exit;
}

// Include file controller
require_once $controller_file;

// Cek class controller ada?
if (!class_exists($controller_class)) {
    http_response_code(500);
    echo "❌ Class <code>$controller_class</code> tidak ditemukan di file.";
    exit;
}

// Buat instance controller
$controller_instance = new $controller_class();

// Cek method/action ada?
if (method_exists($controller_instance, $action)) {
    // Jalankan aksi
    $controller_instance->$action();
} elseif (method_exists($controller_instance, 'index')) {
    // Fallback ke method index()
    $controller_instance->index();
} else {
    http_response_code(404);
    echo "❌ Action <strong>$action()</strong> tidak ditemukan di <code>$controller_class</code>.";
    exit;
}
?>
