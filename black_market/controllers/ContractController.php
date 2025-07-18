<?php
class ContractController {
    private $db;
    private $contract;
    private $category;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->contract = new Contract($this->db);
        $this->category = new Category($this->db);
    }

    public function index() {
        requireLogin();

        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $category_id = $_GET['category'] ?? '';
        $status = $_GET['status'] ?? '';

        // Get categories for filter
        $cat_stmt = $this->category->readAll();
        $categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get contracts
        if (!empty($search) || !empty($category_id) || !empty($status)) {
            $stmt = $this->contract->searchContracts($search, $category_id, $status);
        } else {
            $stmt = $this->contract->readAll();
        }
        
        $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/contract/index.php';
    }

    public function create() {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->contract->user_id = $_SESSION['user_id'];
            $this->contract->title = $_POST['title'] ?? '';
            $this->contract->description = $_POST['description'] ?? '';
            $this->contract->specifications = $_POST['specifications'] ?? '';
            $this->contract->budget_min = $_POST['budget_min'] ?? null;
            $this->contract->budget_max = $_POST['budget_max'] ?? null;
            $this->contract->category_id = $_POST['category_id'] ?? null;
            $this->contract->deadline = $_POST['deadline'] ?? null;

            if (empty($this->contract->title) || empty($this->contract->description)) {
                $error = "Title dan Description harus diisi!";
            } else {
                if ($this->contract->create()) {
                    $_SESSION['success'] = "Contract berhasil dibuat!";
                    redirect("index.php?controller=contract");
                } else {
                    $error = "Gagal membuat contract!";
                }
            }
        }

        // Get categories for dropdown
        $stmt = $this->category->readAll();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/contract/create.php';
    }

    public function myContracts() {
        requireLogin();

        $stmt = $this->contract->readUserContracts($_SESSION['user_id']);
        $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/contract/my_contracts.php';
    }

    public function detail() {
        requireLogin();

        $contract_id = $_GET['id'] ?? '';
        
        if (empty($contract_id)) {
            redirect("index.php?controller=contract");
        }

        $contract_data = $this->contract->readOne($contract_id);
        
        if (!$contract_data) {
            redirect("index.php?controller=contract");
        }

        include 'views/contract/detail.php';
    }

    public function edit() {
        requireLogin();

        $contract_id = $_GET['id'] ?? '';
        
        if (empty($contract_id)) {
            redirect("index.php?controller=contract&action=myContracts");
        }

        $contract_data = $this->contract->readOne($contract_id);
        
        if (!$contract_data || $contract_data['user_id'] != $_SESSION['user_id']) {
            redirect("index.php?controller=contract&action=myContracts");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->contract->contract_id = $contract_id;
            $this->contract->user_id = $_SESSION['user_id'];
            $this->contract->title = $_POST['title'] ?? '';
            $this->contract->description = $_POST['description'] ?? '';
            $this->contract->specifications = $_POST['specifications'] ?? '';
            $this->contract->budget_min = $_POST['budget_min'] ?? null;
            $this->contract->budget_max = $_POST['budget_max'] ?? null;
            $this->contract->category_id = $_POST['category_id'] ?? null;
            $this->contract->deadline = $_POST['deadline'] ?? null;
            $this->contract->status = $_POST['status'] ?? 'open';

            if ($this->contract->update()) {
                $_SESSION['success'] = "Contract berhasil diupdate!";
                redirect("index.php?controller=contract&action=myContracts");
            } else {
                $error = "Gagal mengupdate contract!";
            }
        }

        // Get categories for dropdown
        $stmt = $this->category->readAll();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/contract/edit.php';
    }

    public function delete() {
        requireLogin();

        $contract_id = $_GET['id'] ?? '';
        
        if (!empty($contract_id)) {
            if ($this->contract->delete($contract_id, $_SESSION['user_id'])) {
                $_SESSION['success'] = "Contract berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus contract!";
            }
        }

        redirect("index.php?controller=contract&action=myContracts");
    }

    public function contactOwner() {
        requireLogin();

        $contract_id = $_GET['id'] ?? '';
        
        if (empty($contract_id)) {
            redirect("index.php?controller=contract");
        }

        $contract_data = $this->contract->readOne($contract_id);
        
        if (!$contract_data || $contract_data['user_id'] == $_SESSION['user_id']) {
            redirect("index.php?controller=contract");
        }

        // Create initial message about the contract
        $chat = new Chat($this->db);
        $chat->sender_id = $_SESSION['user_id'];
        $chat->receiver_id = $contract_data['user_id'];
        $chat->message = "Halo, saya tertarik dengan contract Anda: \"" . $contract_data['title'] . 
                        "\" (Contract ID: #" . $contract_id . ")";
        $chat->sendMessage();

        redirect("index.php?controller=chat&action=conversation&user_id=" . $contract_data['user_id']);
    }
}
?>