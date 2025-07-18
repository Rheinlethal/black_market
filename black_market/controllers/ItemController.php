<?php
class ItemController {
    private $db;
    private $item;
    private $category;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->item = new Item($this->db);
        $this->category = new Category($this->db);
    }

    public function manage() {
        requireLogin();
        
        // Check if user is admin (optional - you can add admin check here)
        if ($_SESSION['username'] !== 'admin') {
            redirect("index.php");
        }

        $stmt = $this->item->readAll();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/item/manage.php';
    }

    public function add() {
        requireLogin();
        
        // Check if user is admin
        if ($_SESSION['username'] !== 'admin') {
            redirect("index.php");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->item->item_name = $_POST['item_name'] ?? '';
            $this->item->description = $_POST['description'] ?? '';
            $this->item->categories_id_categories = $_POST['categories_id_categories'] ?? '';

            if (empty($this->item->item_name) || empty($this->item->description) || empty($this->item->categories_id_categories)) {
                $error = "Semua field harus diisi!";
            } else {
                if ($this->item->create()) {
                    $_SESSION['success'] = "Item berhasil ditambahkan!";
                    redirect("index.php?controller=item&action=manage");
                } else {
                    $error = "Gagal menambahkan item!";
                }
            }
        }

        // Get categories for dropdown
        $stmt = $this->category->readAll();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/item/add.php';
    }
}
?>