<?php include 'views/layout/header.php'; ?>

<div class="chat-container">
    <div class="card" style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Chat dengan <?= htmlspecialchars($other_user['username']) ?></h2>
            <a href="<?= BASE_URL ?>index.php?controller=chat" class="btn" style="background-color: #666;">Kembali</a>
        </div>
    </div>

    <div class="chat-messages" id="chatMessages">
        <?php if (empty($messages)): ?>
            <p style="text-align: center; color: #999;">Belum ada pesan</p>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div class="message <?= $msg['sender_id'] == $_SESSION['user_id'] ? 'sent' : 'received' ?>" 
                     data-message-id="<?= $msg['message_id'] ?>">
                    <p><?= htmlspecialchars($msg['message']) ?></p>
                    <small class="message-time">
                        <?= date('H:i', strtotime($msg['created_at'])) ?>
                    </small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <form method="POST" class="chat-input">
        <textarea name="message" placeholder="Ketik pesan..." rows="2" required></textarea>
        <button type="submit" class="btn">Kirim</button>
    </form>
</div>

<script>
// Auto scroll to bottom
const chatMessages = document.getElementById('chatMessages');
chatMessages.scrollTop = chatMessages.scrollHeight;

// Auto refresh messages every 3 seconds
let lastMessageId = 0;
const messages = document.querySelectorAll('.message');
if (messages.length > 0) {
    lastMessageId = messages[messages.length - 1].getAttribute('data-message-id');
}

function loadNewMessages() {
    fetch('<?= BASE_URL ?>index.php?controller=chat&action=getNewMessages&user_id=<?= $other_user_id ?>&last_id=' + lastMessageId)
        .then(response => response.json())
        .then(data => {
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'message ' + (msg.sender_id == <?= $_SESSION['user_id'] ?> ? 'sent' : 'received');
                    messageDiv.setAttribute('data-message-id', msg.message_id);
                    
                    const messageText = document.createElement('p');
                    messageText.textContent = msg.message;
                    
                    const messageTime = document.createElement('small');
                    messageTime.className = 'message-time';
                    const date = new Date(msg.created_at);
                    messageTime.textContent = date.getHours().toString().padStart(2, '0') + ':' + 
                                            date.getMinutes().toString().padStart(2, '0');
                    
                    messageDiv.appendChild(messageText);
                    messageDiv.appendChild(messageTime);
                    
                    chatMessages.appendChild(messageDiv);
                    lastMessageId = msg.message_id;
                });
                
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        })
        .catch(error => console.error('Error:', error));
}

// Check for new messages every 3 seconds
setInterval(loadNewMessages, 3000);

// Focus on textarea
document.querySelector('textarea[name="message"]').focus();
</script>

<?php include 'views/layout/footer.php'; ?>