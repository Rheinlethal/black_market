<?php
class AdminController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    private function checkAdmin() {
        requireLogin();
        if ($_SESSION['username'] !== 'admin') {
            redirect("index.php");
        }
    }

    public function index() {
        $this->checkAdmin();

        // Get statistics
        // Total users
        $user_query = "SELECT COUNT(*) as total FROM user";
        $user_stmt = $this->db->prepare($user_query);
        $user_stmt->execute();
        $total_users = $user_stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Total orders
        $order_query = "SELECT COUNT(*) as total FROM `order`";
        $order_stmt = $this->db->prepare($order_query);
        $order_stmt->execute();
        $total_orders = $order_stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Total items
        $item_query = "SELECT COUNT(*) as total FROM item";
        $item_stmt = $this->db->prepare($item_query);
        $item_stmt->execute();
        $total_items = $item_stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Total contracts
        $contract_query = "SELECT COUNT(*) as total FROM contract WHERE status = 'open'";
        $contract_stmt = $this->db->prepare($contract_query);
        $contract_stmt->execute();
        $total_contracts = $contract_stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Total transaction value
        $value_query = "SELECT SUM(price * quantity) as total_value FROM `order`";
        $value_stmt = $this->db->prepare($value_query);
        $value_stmt->execute();
        $total_value = $value_stmt->fetch(PDO::FETCH_ASSOC)['total_value'] ?? 0;

        // Recent orders
        $recent_query = "SELECT o.*, i.item_name, u.username, ot.order_type_name 
                        FROM `order` o
                        LEFT JOIN item i ON o.iditem = i.iditem
                        LEFT JOIN user u ON o.user_id = u.user_id
                        LEFT JOIN order_type ot ON o.order_type = ot.id_order_type
                        ORDER BY o.created_at DESC
                        LIMIT 5";
        $recent_stmt = $this->db->prepare($recent_query);
        $recent_stmt->execute();
        $recent_orders = $recent_stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/admin/dashboard.php';
    }

    // User Management
    public function users() {
        $this->checkAdmin();

        $query = "SELECT u.*, 
                         (SELECT COUNT(*) FROM `order` WHERE user_id = u.user_id) as order_count,
                         (SELECT COUNT(*) FROM messages WHERE sender_id = u.user_id OR receiver_id = u.user_id) as message_count
                  FROM user u
                  ORDER BY u.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/admin/users.php';
    }

    public function deleteUser() {
        $this->checkAdmin();

        $user_id = $_GET['id'] ?? '';
        
        if (empty($user_id) || $user_id == $_SESSION['user_id']) {
            $_SESSION['error'] = "Tidak dapat menghapus user!";
            redirect("index.php?controller=admin&action=users");
        }

        // Check if trying to delete admin
        $check_query = "SELECT username FROM user WHERE user_id = :user_id";
        $check_stmt = $this->db->prepare($check_query);
        $check_stmt->bindParam(":user_id", $user_id);
        $check_stmt->execute();
        $user = $check_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && $user['username'] === 'admin') {
            $_SESSION['error'] = "Admin account tidak dapat dihapus!";
            redirect("index.php?controller=admin&action=users");
        }

        try {
            // Start transaction
            $this->db->beginTransaction();
            
            // Manual cascade delete if CASCADE not set in database
            // Delete messages
            $del_msg = "DELETE FROM messages WHERE sender_id = :user_id OR receiver_id = :user_id";
            $stmt = $this->db->prepare($del_msg);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();
            
            // Delete conversations if table exists
            try {
                $del_conv = "DELETE FROM conversations WHERE user1_id = :user_id OR user2_id = :user_id";
                $stmt = $this->db->prepare($del_conv);
                $stmt->bindParam(":user_id", $user_id);
                $stmt->execute();
            } catch (PDOException $e) {
                // Ignore if conversations table doesn't exist
            }
            
            // Delete orders
            $del_order = "DELETE FROM `order` WHERE user_id = :user_id";
            $stmt = $this->db->prepare($del_order);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();
            
            // Finally delete user
            $query = "DELETE FROM user WHERE user_id = :user_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();
            
            // Commit transaction
            $this->db->commit();
            $_SESSION['success'] = "User dan semua data terkait berhasil dihapus!";
            
        } catch (PDOException $e) {
            // Rollback transaction on error
            $this->db->rollback();
            $_SESSION['error'] = "Gagal menghapus user: " . $e->getMessage();
        }

        redirect("index.php?controller=admin&action=users");
    }

    public function editUser() {
        $this->checkAdmin();

        $user_id = $_GET['id'] ?? '';
        
        if (empty($user_id)) {
            redirect("index.php?controller=admin&action=users");
        }

        // Get user data
        $query = "SELECT * FROM user WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            redirect("index.php?controller=admin&action=users");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $new_password = $_POST['new_password'] ?? '';

            // Prevent changing admin username
            if ($user['username'] === 'admin' && $username !== 'admin') {
                $error = "Tidak dapat mengubah username admin!";
            } else {
                // Update user
                if (!empty($new_password)) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_query = "UPDATE user SET username = :username, email = :email, password = :password WHERE user_id = :user_id";
                    $update_stmt = $this->db->prepare($update_query);
                    $update_stmt->bindParam(":password", $hashed_password);
                } else {
                    $update_query = "UPDATE user SET username = :username, email = :email WHERE user_id = :user_id";
                    $update_stmt = $this->db->prepare($update_query);
                }

                $update_stmt->bindParam(":username", $username);
                $update_stmt->bindParam(":email", $email);
                $update_stmt->bindParam(":user_id", $user_id);

                if ($update_stmt->execute()) {
                    $_SESSION['success'] = "User berhasil diupdate!";
                    redirect("index.php?controller=admin&action=users");
                } else {
                    $error = "Gagal mengupdate user!";
                }
            }
        }

        include 'views/admin/edit_user.php';
    }

    // Order Management
    public function orders() {
        $this->checkAdmin();

        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $order_type = $_GET['order_type'] ?? '';
        $username = $_GET['username'] ?? '';

        $query = "SELECT o.*, i.item_name, i.description, c.category_name, 
                         u.username, ot.order_type_name
                  FROM `order` o
                  LEFT JOIN item i ON o.iditem = i.iditem
                  LEFT JOIN categories c ON i.categories_id_categories = c.id_categories
                  LEFT JOIN user u ON o.user_id = u.user_id
                  LEFT JOIN order_type ot ON o.order_type = ot.id_order_type
                  WHERE 1=1";

        if (!empty($search)) {
            $query .= " AND (i.item_name LIKE :search OR i.description LIKE :search)";
        }
        if (!empty($order_type)) {
            $query .= " AND o.order_type = :order_type";
        }
        if (!empty($username)) {
            $query .= " AND u.username LIKE :username";
        }

        $query .= " ORDER BY o.created_at DESC";

        $stmt = $this->db->prepare($query);

        if (!empty($search)) {
            $search_term = "%{$search}%";
            $stmt->bindParam(":search", $search_term);
        }
        if (!empty($order_type)) {
            $stmt->bindParam(":order_type", $order_type);
        }
        if (!empty($username)) {
            $username_term = "%{$username}%";
            $stmt->bindParam(":username", $username_term);
        }

        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/admin/orders.php';
    }

    public function deleteOrder() {
        $this->checkAdmin();

        $order_id = $_GET['id'] ?? '';
        
        if (empty($order_id)) {
            $_SESSION['error'] = "Order tidak ditemukan!";
            redirect("index.php?controller=admin&action=orders");
        }

        $query = "DELETE FROM `order` WHERE order_id = :order_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":order_id", $order_id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Order berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus order!";
        }

        redirect("index.php?controller=admin&action=orders");
    }

    public function priceAnomalyDetection()
    {
        $db = new Database();

        $conn = $db->getConnection();
        $stmt = $conn->query("SELECT * FROM price_anomaly_detection");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/admin/price_anomaly_detection.php';
    }

    public function suspiciousNetworkBase()
    {
        $db = new Database();
        $conn = $db->getConnection();
        $stmt = $conn->query("SELECT * FROM price_anomaly_detection");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


        include 'views/admin/suspicious_network.php';
    }

    public function trustAnalysis()
    {

        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->query("CALL sp_user_trust_analysis()");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/admin/trust_analysis.php';
    }

    public function marketIntelligence()
{


    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->query("SELECT * FROM market_intelligence");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    include 'views/admin/market_intelligence.php';
}

}
?>