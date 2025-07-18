<?php
class Contract {
    private $conn;
    private $table_name = "contract";

    public $contract_id;
    public $user_id;
    public $title;
    public $description;
    public $specifications;
    public $budget_min;
    public $budget_max;
    public $category_id;
    public $status;
    public $deadline;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, title, description, specifications, budget_min, budget_max, category_id, deadline) 
                  VALUES (:user_id, :title, :description, :specifications, :budget_min, :budget_max, :category_id, :deadline)";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->specifications = htmlspecialchars(strip_tags($this->specifications));

        // Bind values
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":specifications", $this->specifications);
        $stmt->bindParam(":budget_min", $this->budget_min);
        $stmt->bindParam(":budget_max", $this->budget_max);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":deadline", $this->deadline);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function readAll() {
        $query = "SELECT c.*, u.username, cat.category_name,
                         (SELECT COUNT(*) FROM messages WHERE 
                          (sender_id = c.user_id OR receiver_id = c.user_id) 
                          AND message LIKE CONCAT('%contract%', c.contract_id, '%')) as response_count
                  FROM " . $this->table_name . " c
                  LEFT JOIN user u ON c.user_id = u.user_id
                  LEFT JOIN categories cat ON c.category_id = cat.id_categories
                  WHERE c.status = 'open'
                  ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function readUserContracts($user_id) {
        $query = "SELECT c.*, cat.category_name,
                         (SELECT COUNT(*) FROM messages WHERE 
                          receiver_id = :user_id 
                          AND message LIKE CONCAT('%contract%', c.contract_id, '%')) as response_count
                  FROM " . $this->table_name . " c
                  LEFT JOIN categories cat ON c.category_id = cat.id_categories
                  WHERE c.user_id = :user_id
                  ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT c.*, u.username, u.user_id, cat.category_name
                  FROM " . $this->table_name . " c
                  LEFT JOIN user u ON c.user_id = u.user_id
                  LEFT JOIN categories cat ON c.category_id = cat.id_categories
                  WHERE c.contract_id = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->contract_id = $row['contract_id'];
            $this->user_id = $row['user_id'];
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->specifications = $row['specifications'];
            $this->budget_min = $row['budget_min'];
            $this->budget_max = $row['budget_max'];
            $this->category_id = $row['category_id'];
            $this->status = $row['status'];
            $this->deadline = $row['deadline'];
            $this->created_at = $row['created_at'];
            return $row;
        }

        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET title = :title,
                      description = :description,
                      specifications = :specifications,
                      budget_min = :budget_min,
                      budget_max = :budget_max,
                      category_id = :category_id,
                      deadline = :deadline,
                      status = :status
                  WHERE contract_id = :contract_id AND user_id = :user_id";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->specifications = htmlspecialchars(strip_tags($this->specifications));

        // Bind values
        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":specifications", $this->specifications);
        $stmt->bindParam(":budget_min", $this->budget_min);
        $stmt->bindParam(":budget_max", $this->budget_max);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":deadline", $this->deadline);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":contract_id", $this->contract_id);
        $stmt->bindParam(":user_id", $this->user_id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete($contract_id, $user_id) {
        $query = "DELETE FROM " . $this->table_name . " 
                  WHERE contract_id = :contract_id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":contract_id", $contract_id);
        $stmt->bindParam(":user_id", $user_id);
        
        if ($stmt->execute()) {
            return true;
        }
        
        return false;
    }

    public function searchContracts($search = '', $category_id = '', $status = '', $min_budget = '', $max_budget = '') {
        $query = "SELECT c.*, u.username, cat.category_name
                  FROM " . $this->table_name . " c
                  LEFT JOIN user u ON c.user_id = u.user_id
                  LEFT JOIN categories cat ON c.category_id = cat.id_categories
                  WHERE 1=1";

        if (!empty($search)) {
            $query .= " AND (c.title LIKE :search OR c.description LIKE :search OR c.specifications LIKE :search)";
        }
        if (!empty($category_id)) {
            $query .= " AND c.category_id = :category_id";
        }
        if (!empty($status)) {
            $query .= " AND c.status = :status";
        }
        if (!empty($min_budget)) {
            $query .= " AND c.budget_max >= :min_budget";
        }
        if (!empty($max_budget)) {
            $query .= " AND c.budget_min <= :max_budget";
        }

        $query .= " ORDER BY c.created_at DESC";

        $stmt = $this->conn->prepare($query);

        if (!empty($search)) {
            $search_term = "%{$search}%";
            $stmt->bindParam(":search", $search_term);
        }
        if (!empty($category_id)) {
            $stmt->bindParam(":category_id", $category_id);
        }
        if (!empty($status)) {
            $stmt->bindParam(":status", $status);
        }
        if (!empty($min_budget)) {
            $stmt->bindParam(":min_budget", $min_budget);
        }
        if (!empty($max_budget)) {
            $stmt->bindParam(":max_budget", $max_budget);
        }

        $stmt->execute();
        return $stmt;
    }
}
?>