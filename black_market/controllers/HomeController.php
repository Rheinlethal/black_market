<?php
class HomeController {
    private $db;
    private $order;
    private $category;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->order = new Order($this->db);
        $this->category = new Category($this->db);
    }

    public function index() {
        requireLogin();

        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $category_id = $_GET['category'] ?? '';
        $order_type = $_GET['order_type'] ?? '';
        $min_price = $_GET['min_price'] ?? '';
        $max_price = $_GET['max_price'] ?? '';

        // Get categories for filter dropdown
        $stmt = $this->category->readAll();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($search) || !empty($category_id) || !empty($order_type) || !empty($min_price) || !empty($max_price)) {
            // Use search and filter
            $stmt = $this->order->searchAndFilter($search, $category_id, $order_type, $min_price, $max_price);
            $all_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Separate into WTS and WTB
            $wts_orders = array_filter($all_orders, function($order) {
                return $order['order_type'] == 1;
            });
            
            $wtb_orders = array_filter($all_orders, function($order) {
                return $order['order_type'] == 2;
            });
        } else {
            // Get WTS (Want to Sell) orders - order_type = 1
            $wts_stmt = $this->order->readOrdersByType(1);
            $wts_orders = $wts_stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get WTB (Want to Buy) orders - order_type = 2
            $wtb_stmt = $this->order->readOrdersByType(2);
            $wtb_orders = $wtb_stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        include 'views/home/index.php';
    }
}
?>