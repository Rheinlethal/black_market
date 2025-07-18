<?php include 'views/layout/header.php'; ?>

<h1 style="text-align: center; margin-bottom: 30px;">Percakapan Saya</h1>

<div class="chat-container">
    <?php if (empty($conversations)): ?>
        <div class="card">
            <p style="text-align: center; color: #999;">Belum ada percakapan</p>
            <p style="text-align: center; margin-top: 10px;">
                <small>Mulai chat dengan mengklik tombol Chat pada order di halaman utama</small>
            </p>
        </div>
    <?php else: ?>
        <?php foreach ($conversations as $conv): ?>
            <a href="<?= BASE_URL ?>index.php?controller=chat&action=conversation&user_id=<?= $conv['other_user_id'] ?>" 
               style="text-decoration: none; color: inherit;">
                <div class="conversation-item">
                    <div>
                        <h3><?= htmlspecialchars($conv['other_username']) ?></h3>
                        <p style="margin: 5px 0; color: #aaa;">
                            <?= htmlspecialchars(substr($conv['last_message'], 0, 50)) ?>...
                        </p>
                        <small class="message-time">
                            <?= date('d/m/Y H:i', strtotime($conv['last_message_time'])) ?>
                        </small>
                    </div>
                    <?php if ($conv['unread_count'] > 0): ?>
                        <div>
                            <span class="unread-badge"><?= $conv['unread_count'] ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'views/layout/footer.php'; ?>