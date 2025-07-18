<?php
// Fix Admin Panel Script
// Jalankan file ini untuk membuat AdminController jika belum ada

echo "<h2>Fix Admin Panel Script</h2>";

// 1. Check if controllers directory exists
$controllers_dir = __DIR__ . '/controllers/';
if (!is_dir($controllers_dir)) {
    echo "Creating controllers directory...<br>";
    mkdir($controllers_dir, 0755, true);
}

// 2. Create AdminController.php
$admin_controller_content = '<?php
class AdminController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    private function checkAdmin() {
        requireLogin();
        if ($_SESSION[\'username\'] !== \'admin\') {
            redirect("index.php");
        }
    }

    public function index() {
        $this->checkAdmin();

        // Simple dashboard for now
        include \'views/admin/dashboard_simple.php\';
    }

    public function users() {
        $this->checkAdmin();
        echo "<h1>User Management</h1>";
        echo "<p>Feature coming soon...</p>";
        echo "<a href=\'" . BASE_URL . "index.php?controller=admin\'>Back to Dashboard</a>";
    }

    public function orders() {
        $this->checkAdmin();
        echo "<h1>Order Management</h1>";
        echo "<p>Feature coming soon...</p>";
        echo "<a href=\'" . BASE_URL . "index.php?controller=admin\'>Back to Dashboard</a>";
    }
}
?>';

$file_path = $controllers_dir . 'AdminController.php';
if (file_put_contents($file_path, $admin_controller_content)) {
    echo "✓ AdminController.php created successfully!<br>";
} else {
    echo "✗ Failed to create AdminController.php<br>";
}

// 3. Create simple dashboard view
$views_admin_dir = __DIR__ . '/views/admin/';
if (!is_dir($views_admin_dir)) {
    echo "Creating views/admin directory...<br>";
    mkdir($views_admin_dir, 0755, true);
}

$dashboard_content = '<?php include \'views/layout/header.php\'; ?>

<h1 style="text-align: center; margin-bottom: 30px;">🛡️ Admin Dashboard</h1>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
    <div class="card" style="background-color: #1976D2;">
        <h2>Users</h2>
        <p>Manage users</p>
        <a href="<?= BASE_URL ?>index.php?controller=admin&action=users" class="btn">
            Manage →
        </a>
    </div>
    
    <div class="card" style="background-color: #388E3C;">
        <h2>Orders</h2>
        <p>Manage orders</p>
        <a href="<?= BASE_URL ?>index.php?controller=admin&action=orders" class="btn">
            Manage →
        </a>
    </div>
    
    <div class="card" style="background-color: #F57C00;">
        <h2>Items</h2>
        <p>Manage items</p>
        <a href="<?= BASE_URL ?>index.php?controller=item&action=manage" class="btn">
            Manage →
        </a>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <h2>Quick Actions</h2>
    <a href="<?= BASE_URL ?>index.php" class="btn" style="background-color: #666;">
        Back to Market
    </a>
</div>

<?php include \'views/layout/footer.php\'; ?>';

$dashboard_path = $views_admin_dir . 'dashboard_simple.php';
if (file_put_contents($dashboard_path, $dashboard_content)) {
    echo "✓ Dashboard view created successfully!<br>";
} else {
    echo "✗ Failed to create dashboard view<br>";
}

// 4. Test the result
echo "<br><h3>Testing...</h3>";
if (file_exists($file_path)) {
    echo "✓ AdminController.php exists<br>";
    require_once 'config/core.php';
    if (class_exists('AdminController')) {
        echo "✓ AdminController class can be loaded<br>";
        echo "<br><strong>Admin Panel should now work!</strong><br>";
        echo '<a href="index.php?controller=admin">Go to Admin Panel</a>';
    } else {
        echo "✗ AdminController class cannot be loaded<br>";
    }
} else {
    echo "✗ AdminController.php not found<br>";
}

echo "<br><br><small>Script completed. If still not working, check file permissions.</small>";
?>