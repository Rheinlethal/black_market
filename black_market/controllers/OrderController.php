<?php
class OrderController {
    private $db;
    private $order;
    private $item;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->order = new Order($this->db);
        $this->item = new Item($this->db);
    }

    public function create() {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->order->iditem = $_POST['iditem'] ?? '';
            $this->order->order_type = $_POST['order_type'] ?? '';
            $this->order->quantity = $_POST['quantity'] ?? '';
            $this->order->price = $_POST['price'] ?? '';
            $this->order->user_id = $_SESSION['user_id'];

            if (empty($this->order->iditem) || empty($this->order->order_type) || 
                empty($this->order->quantity) || empty($this->order->price)) {
                $error = "Semua field harus diisi!";
            } else {
                if ($this->order->create()) {
                    $success = "Order berhasil dibuat!";
                    redirect("index.php");
                } else {
                    $error = "Gagal membuat order!";
                }
            }
        }

        // Get all items for dropdown
        $stmt = $this->item->readAll();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/order/create.php';
    }

    public function myOrders() {
        requireLogin();

        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $category_id = $_GET['category'] ?? '';
        $order_type = $_GET['order_type'] ?? '';

        // Get categories for filter
        $category = new Category($this->db);
        $cat_stmt = $category->readAll();
        $categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Build query with filters
        $query = "SELECT o.*, i.item_name, i.description, c.category_name, 
                         ot.order_type_name
                  FROM `order` o
                  LEFT JOIN item i ON o.iditem = i.iditem
                  LEFT JOIN categories c ON i.categories_id_categories = c.id_categories
                  LEFT JOIN order_type ot ON o.order_type = ot.id_order_type
                  WHERE o.user_id = :user_id";

        if (!empty($search)) {
            $query .= " AND (i.item_name LIKE :search OR i.description LIKE :search)";
        }
        if (!empty($category_id)) {
            $query .= " AND i.categories_id_categories = :category_id";
        }
        if (!empty($order_type)) {
            $query .= " AND o.order_type = :order_type";
        }

        $query .= " ORDER BY o.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":user_id", $_SESSION['user_id']);

        if (!empty($search)) {
            $search_term = "%{$search}%";
            $stmt->bindParam(":search", $search_term);
        }
        if (!empty($category_id)) {
            $stmt->bindParam(":category_id", $category_id);
        }
        if (!empty($order_type)) {
            $stmt->bindParam(":order_type", $order_type);
        }

        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/order/my_orders.php';
    }

    public function delete() {
        requireLogin();

        $order_id = $_GET['id'] ?? '';
        
        if (empty($order_id)) {
            $_SESSION['error'] = "Order tidak ditemukan!";
            redirect("index.php?controller=order&action=myOrders");
        }

        if ($this->order->delete($order_id, $_SESSION['user_id'])) {
            $_SESSION['success'] = "Order berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus order!";
        }

        redirect("index.php?controller=order&action=myOrders");
    }
}
?>