<?php
// Debug file untuk cek admin access
// Letakkan di root folder aplikasi dan akses langsung

require_once 'config/core.php';

echo "<h2>Debug Admin Access</h2>";

// 1. Check session
echo "<h3>1. Session Check:</h3>";
echo "Session ID: " . session_id() . "<br>";
echo "Logged in: " . (isLoggedIn() ? 'Yes' : 'No') . "<br>";
echo "Username: " . ($_SESSION['username'] ?? 'Not set') . "<br>";
echo "User ID: " . ($_SESSION['user_id'] ?? 'Not set') . "<br>";
echo "Is Admin: " . (($_SESSION['username'] ?? '') === 'admin' ? 'Yes' : 'No') . "<br>";

// 2. Check AdminController file
echo "<h3>2. AdminController File Check:</h3>";
$controller_file = 'controllers/AdminController.php';
echo "File exists: " . (file_exists($controller_file) ? 'Yes' : 'No') . "<br>";
if (file_exists($controller_file)) {
    echo "File readable: " . (is_readable($controller_file) ? 'Yes' : 'No') . "<br>";
    echo "File size: " . filesize($controller_file) . " bytes<br>";
}

// 3. Check URL generation
echo "<h3>3. URL Generation:</h3>";
echo "BASE_URL: " . BASE_URL . "<br>";
echo "Admin URL: " . BASE_URL . "index.php?controller=admin<br>";

// 4. Try to load AdminController
echo "<h3>4. Try Loading AdminController:</h3>";
try {
    if (class_exists('AdminController')) {
        echo "AdminController class already loaded<br>";
    } else {
        echo "AdminController class not loaded, trying to load...<br>";
        require_once 'controllers/AdminController.php';
        if (class_exists('AdminController')) {
            echo "AdminController loaded successfully<br>";
        } else {
            echo "Failed to load AdminController<br>";
        }
    }
} catch (Exception $e) {
    echo "Error loading AdminController: " . $e->getMessage() . "<br>";
}

// 5. Check allowed controllers
echo "<h3>5. Allowed Controllers:</h3>";
$allowed_controllers = ['home', 'auth', 'order', 'item', 'chat', 'search', 'admin'];
echo "Admin in allowed controllers: " . (in_array('admin', $allowed_controllers) ? 'Yes' : 'No') . "<br>";

// 6. Manual test link
echo "<h3>6. Test Links:</h3>";
echo '<a href="' . BASE_URL . 'index.php?controller=admin">Test Admin Panel Link</a><br>';
echo '<a href="' . BASE_URL . 'index.php?controller=admin&action=users">Test Admin Users Link</a><br>';

// 7. PHP Error reporting
echo "<h3>7. PHP Error Reporting:</h3>";
echo "Error reporting level: " . error_reporting() . "<br>";
echo "Display errors: " . ini_get('display_errors') . "<br>";

// 8. Test Database connection
echo "<h3>8. Database Connection:</h3>";
try {
    $database = new Database();
    $db = $database->getConnection();
    echo "Database connected successfully<br>";
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "<br>";
}
?>