<?php
class Item {
    private $conn;
    private $table_name = "item";

    public $iditem;
    public $item_name;
    public $description;
    public $image;
    public $categories_id_categories;
    public $category_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT i.*, c.category_name 
                  FROM " . $this->table_name . " i
                  LEFT JOIN categories c ON i.categories_id_categories = c.id_categories
                  ORDER BY i.item_name";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT i.*, c.category_name 
                  FROM " . $this->table_name . " i
                  LEFT JOIN categories c ON i.categories_id_categories = c.id_categories
                  WHERE i.iditem = :id
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->iditem = $row['iditem'];
            $this->item_name = $row['item_name'];
            $this->description = $row['description'];
            $this->image = $row['image'];
            $this->categories_id_categories = $row['categories_id_categories'];
            $this->category_name = $row['category_name'];
            return true;
        }

        return false;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (item_name, description, categories_id_categories) 
                  VALUES (:item_name, :description, :categories_id_categories)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":item_name", $this->item_name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":categories_id_categories", $this->categories_id_categories);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>