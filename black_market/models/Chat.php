<?php
class Chat {
    private $conn;
    private $table_name = "messages";

    public $message_id;
    public $sender_id;
    public $receiver_id;
    public $message;
    public $created_at;
    public $is_read;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function sendMessage() {
        $query = "INSERT INTO " . $this->table_name . " 
                  (sender_id, receiver_id, message) 
                  VALUES (:sender_id, :receiver_id, :message)";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->message = htmlspecialchars(strip_tags($this->message));

        $stmt->bindParam(":sender_id", $this->sender_id);
        $stmt->bindParam(":receiver_id", $this->receiver_id);
        $stmt->bindParam(":message", $this->message);

        if ($stmt->execute()) {
            // Update conversation table
            $this->updateConversation();
            return true;
        }

        return false;
    }

    public function getMessages($user1_id, $user2_id) {
        $query = "SELECT m.*, 
                         s.username as sender_name, 
                         r.username as receiver_name
                  FROM " . $this->table_name . " m
                  LEFT JOIN user s ON m.sender_id = s.user_id
                  LEFT JOIN user r ON m.receiver_id = r.user_id
                  WHERE (m.sender_id = :user1_id AND m.receiver_id = :user2_id)
                     OR (m.sender_id = :user2_id AND m.receiver_id = :user1_id)
                  ORDER BY m.created_at ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user1_id", $user1_id);
        $stmt->bindParam(":user2_id", $user2_id);
        $stmt->execute();

        return $stmt;
    }

    public function markAsRead($sender_id, $receiver_id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET is_read = 1 
                  WHERE sender_id = :sender_id AND receiver_id = :receiver_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":sender_id", $sender_id);
        $stmt->bindParam(":receiver_id", $receiver_id);
        
        return $stmt->execute();
    }

    public function getConversations($user_id) {
        $query = "SELECT DISTINCT 
                    CASE 
                        WHEN m.sender_id = :user_id THEN m.receiver_id 
                        ELSE m.sender_id 
                    END as other_user_id,
                    u.username as other_username,
                    MAX(m.created_at) as last_message_time,
                    (SELECT message FROM messages 
                     WHERE (sender_id = :user_id AND receiver_id = other_user_id) 
                        OR (sender_id = other_user_id AND receiver_id = :user_id)
                     ORDER BY created_at DESC LIMIT 1) as last_message,
                    (SELECT COUNT(*) FROM messages 
                     WHERE sender_id = other_user_id 
                       AND receiver_id = :user_id 
                       AND is_read = 0) as unread_count
                  FROM messages m
                  JOIN user u ON (
                    CASE 
                        WHEN m.sender_id = :user_id THEN m.receiver_id = u.user_id
                        ELSE m.sender_id = u.user_id
                    END
                  )
                  WHERE m.sender_id = :user_id OR m.receiver_id = :user_id
                  GROUP BY other_user_id, u.username
                  ORDER BY last_message_time DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        return $stmt;
    }

    public function getUnreadCount($user_id) {
        $query = "SELECT COUNT(*) as unread_count 
                  FROM " . $this->table_name . " 
                  WHERE receiver_id = :user_id AND is_read = 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['unread_count'];
    }

    private function updateConversation() {
        // Ensure user1_id is always the smaller ID for consistency
        $user1 = min($this->sender_id, $this->receiver_id);
        $user2 = max($this->sender_id, $this->receiver_id);

        $query = "INSERT INTO conversations (user1_id, user2_id, last_message_time) 
                  VALUES (:user1, :user2, NOW()) 
                  ON DUPLICATE KEY UPDATE last_message_time = NOW()";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user1", $user1);
        $stmt->bindParam(":user2", $user2);
        $stmt->execute();
    }
}
?>