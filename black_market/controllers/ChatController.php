<?php
class ChatController {
    private $db;
    private $chat;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->chat = new Chat($this->db);
        $this->user = new User($this->db);
    }

    public function index() {
        requireLogin();

        $stmt = $this->chat->getConversations($_SESSION['user_id']);
        $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        include 'views/chat/index.php';
    }

    public function conversation() {
        requireLogin();

        $other_user_id = $_GET['user_id'] ?? '';
        
        if (empty($other_user_id) || $other_user_id == $_SESSION['user_id']) {
            redirect("index.php?controller=chat");
        }

        // Get other user info
        $query = "SELECT username FROM user WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":user_id", $other_user_id);
        $stmt->execute();
        
        $other_user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$other_user) {
            redirect("index.php?controller=chat");
        }

        // Mark messages as read
        $this->chat->markAsRead($other_user_id, $_SESSION['user_id']);

        // Get messages
        $stmt = $this->chat->getMessages($_SESSION['user_id'], $other_user_id);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Handle send message
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->chat->sender_id = $_SESSION['user_id'];
            $this->chat->receiver_id = $other_user_id;
            $this->chat->message = $_POST['message'] ?? '';

            if (!empty($this->chat->message)) {
                if ($this->chat->sendMessage()) {
                    redirect("index.php?controller=chat&action=conversation&user_id=" . $other_user_id);
                }
            }
        }

        include 'views/chat/conversatition.php';
    }

    public function startChat() {
        requireLogin();

        $order_user_id = $_GET['user_id'] ?? '';
        $order_id = $_GET['order_id'] ?? '';
        
        if (empty($order_user_id) || $order_user_id == $_SESSION['user_id']) {
            redirect("index.php");
        }

        // Optional: Create initial message about the order
        if (!empty($order_id)) {
            // Get order info
            $query = "SELECT o.*, i.item_name, ot.order_type_name 
                      FROM `order` o
                      LEFT JOIN item i ON o.iditem = i.iditem
                      LEFT JOIN order_type ot ON o.order_type = ot.id_order_type
                      WHERE o.order_id = :order_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":order_id", $order_id);
            $stmt->execute();
            
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($order) {
                $initial_message = "Halo, saya tertarik dengan order " . $order['order_type_name'] . 
                                 " untuk " . $order['item_name'] . " (Qty: " . $order['quantity'] . 
                                 ", Harga: Rp " . number_format($order['price'], 0, ',', '.') . ")";
                
                $this->chat->sender_id = $_SESSION['user_id'];
                $this->chat->receiver_id = $order_user_id;
                $this->chat->message = $initial_message;
                $this->chat->sendMessage();
            }
        }

        redirect("index.php?controller=chat&action=conversation&user_id=" . $order_user_id);
    }

    // AJAX endpoint for getting new messages
    public function getNewMessages() {
        requireLogin();
        
        header('Content-Type: application/json');
        
        $other_user_id = $_GET['user_id'] ?? '';
        $last_message_id = $_GET['last_id'] ?? 0;
        
        if (empty($other_user_id)) {
            echo json_encode(['messages' => []]);
            exit;
        }

        $query = "SELECT m.*, s.username as sender_name 
                  FROM messages m
                  LEFT JOIN user s ON m.sender_id = s.user_id
                  WHERE m.message_id > :last_id
                    AND ((m.sender_id = :user1_id AND m.receiver_id = :user2_id)
                     OR (m.sender_id = :user2_id AND m.receiver_id = :user1_id))
                  ORDER BY m.created_at ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":last_id", $last_message_id);
        $stmt->bindParam(":user1_id", $_SESSION['user_id']);
        $stmt->bindParam(":user2_id", $other_user_id);
        $stmt->execute();

        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Mark as read
        if (!empty($messages)) {
            $this->chat->markAsRead($other_user_id, $_SESSION['user_id']);
        }

        echo json_encode(['messages' => $messages]);
        exit;
    }
}
?>