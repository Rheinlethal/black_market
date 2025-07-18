<?php
class SearchController {
    private $db;
    private $order;
    private $category;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->order = new Order($this->db);
        $this->category = new Category($this->db);
        $this->user = new User($this->db);
    }

    public function advanced() {
        requireLogin();

        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $category_id = $_GET['category'] ?? '';
        $order_type = $_GET['order_type'] ?? '';
        $min_price = $_GET['min_price'] ?? '';
        $max_price = $_GET['max_price'] ?? '';
        $username = $_GET['username'] ?? '';
        $sort_by = $_GET['sort_by'] ?? 'created_at';
        $sort_order = $_GET['sort_order'] ?? 'DESC';

        // Get categories for filter dropdown
        $stmt = $this->category->readAll();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get users for filter dropdown
        $user_query = "SELECT DISTINCT u.user_id, u.username 
                       FROM user u 
                       JOIN `order` o ON u.user_id = o.user_id 
                       ORDER BY u.username";
        $user_stmt = $this->db->prepare($user_query);
        $user_stmt->execute();
        $users = $user_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Build query with advanced filters
        $query = "SELECT o.*, i.item_name, i.description, c.category_name, 
                         u.username, u.user_id, ot.order_type_name
                  FROM `order` o
                  LEFT JOIN item i ON o.iditem = i.iditem
                  LEFT JOIN categories c ON i.categories_id_categories = c.id_categories
                  LEFT JOIN user u ON o.user_id = u.user_id
                  LEFT JOIN order_type ot ON o.order_type = ot.id_order_type
                  WHERE 1=1";

        // Add filters
        if (!empty($search)) {
            $query .= " AND (i.item_name LIKE :search OR i.description LIKE :search)";
        }
        if (!empty($category_id)) {
            $query .= " AND i.categories_id_categories = :category_id";
        }
        if (!empty($order_type)) {
            $query .= " AND o.order_type = :order_type";
        }
        if (!empty($min_price)) {
            $query .= " AND o.price >= :min_price";
        }
        if (!empty($max_price)) {
            $query .= " AND o.price <= :max_price";
        }
        if (!empty($username)) {
            $query .= " AND u.username LIKE :username";
        }

        // Add sorting
        $allowed_sorts = ['created_at', 'price', 'item_name'];
        $sort_by = in_array($sort_by, $allowed_sorts) ? $sort_by : 'created_at';
        $sort_order = ($sort_order === 'ASC') ? 'ASC' : 'DESC';
        
        if ($sort_by === 'item_name') {
            $query .= " ORDER BY i.item_name $sort_order";
        } else {
            $query .= " ORDER BY o.$sort_by $sort_order";
        }

        $stmt = $this->db->prepare($query);

        // Bind parameters
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
        if (!empty($min_price)) {
            $stmt->bindParam(":min_price", $min_price);
        }
        if (!empty($max_price)) {
            $stmt->bindParam(":max_price", $max_price);
        }
        if (!empty($username)) {
            $username_term = "%{$username}%";
            $stmt->bindParam(":username", $username_term);
        }

        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate statistics
        $total_orders = count($orders);
        $total_value = array_sum(array_column($orders, 'price'));
        $avg_price = $total_orders > 0 ? $total_value / $total_orders : 0;

        include 'views/search/advanced.php';
    }
}
?>