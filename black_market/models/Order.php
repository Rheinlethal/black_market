<?php
class Order {
    private $conn;
    private $table_name = "`order`"; // Using backticks because 'order' is a reserved word

    public $order_id;
    public $quantity;
    public $price;
    public $created_at;
    public $iditem;
    public $order_type;
    public $user_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (quantity, price, iditem, order_type, user_id) 
                  VALUES (:quantity, :price, :iditem, :order_type, :user_id)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":iditem", $this->iditem);
        $stmt->bindParam(":order_type", $this->order_type);
        $stmt->bindParam(":user_id", $this->user_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function readAllOrders() {
        $query = "SELECT o.*, i.item_name, i.description, c.category_name, 
                         u.username, ot.order_type_name
                  FROM " . $this->table_name . " o
                  LEFT JOIN item i ON o.iditem = i.iditem
                  LEFT JOIN categories c ON i.categories_id_categories = c.id_categories
                  LEFT JOIN user u ON o.user_id = u.user_id
                  LEFT JOIN order_type ot ON o.order_type = ot.id_order_type
                  ORDER BY o.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function readOrdersByType($order_type) {
        $query = "SELECT o.*, i.item_name, i.description, c.category_name, 
                         u.username, u.user_id, ot.order_type_name
                  FROM " . $this->table_name . " o
                  LEFT JOIN item i ON o.iditem = i.iditem
                  LEFT JOIN categories c ON i.categories_id_categories = c.id_categories
                  LEFT JOIN user u ON o.user_id = u.user_id
                  LEFT JOIN order_type ot ON o.order_type = ot.id_order_type
                  WHERE o.order_type = :order_type
                  ORDER BY o.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_type", $order_type);
        $stmt->execute();

        return $stmt;
    }

    public function readUserOrders($user_id) {
        $query = "SELECT o.*, i.item_name, i.description, c.category_name, 
                         ot.order_type_name
                  FROM " . $this->table_name . " o
                  LEFT JOIN item i ON o.iditem = i.iditem
                  LEFT JOIN categories c ON i.categories_id_categories = c.id_categories
                  LEFT JOIN order_type ot ON o.order_type = ot.id_order_type
                  WHERE o.user_id = :user_id
                  ORDER BY o.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        return $stmt;
    }

    public function searchAndFilter($search = '', $category_id = '', $order_type = '', $min_price = '', $max_price = '') {
        $query = "SELECT o.*, i.item_name, i.description, c.category_name, 
                         u.username, u.user_id, ot.order_type_name
                  FROM " . $this->table_name . " o
                  LEFT JOIN item i ON o.iditem = i.iditem
                  LEFT JOIN categories c ON i.categories_id_categories = c.id_categories
                  LEFT JOIN user u ON o.user_id = u.user_id
                  LEFT JOIN order_type ot ON o.order_type = ot.id_order_type
                  WHERE 1=1";

        // Add search condition
        if (!empty($search)) {
            $query .= " AND (i.item_name LIKE :search OR i.description LIKE :search)";
        }

        // Add category filter
        if (!empty($category_id)) {
            $query .= " AND i.categories_id_categories = :category_id";
        }

        // Add order type filter
        if (!empty($order_type)) {
            $query .= " AND o.order_type = :order_type";
        }

        // Add price range filter
        if (!empty($min_price)) {
            $query .= " AND o.price >= :min_price";
        }
        if (!empty($max_price)) {
            $query .= " AND o.price <= :max_price";
        }

        $query .= " ORDER BY o.created_at DESC";

        $stmt = $this->conn->prepare($query);

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

        $stmt->execute();
        return $stmt;
    }

    public function delete($order_id, $user_id) {
        // First check if the order belongs to the user
        $check_query = "SELECT order_id FROM " . $this->table_name . " 
                       WHERE order_id = :order_id AND user_id = :user_id";
        
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(":order_id", $order_id);
        $check_stmt->bindParam(":user_id", $user_id);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() == 0) {
            return false; // Order not found or doesn't belong to user
        }
        
        // Delete the order
        $query = "DELETE FROM " . $this->table_name . " WHERE order_id = :order_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_id", $order_id);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }
}
?>