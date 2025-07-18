<?php include 'views/layout/header.php'; ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h1>👥 User Management</h1>
    <a href="<?= BASE_URL ?>index.php?controller=admin" class="btn" style="background-color: #666;">
        ← Back to Dashboard
    </a>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success'] ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <?= $_SESSION['error'] ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #555;">
                    <th style="padding: 10px; text-align: left;">ID</th>
                    <th style="padding: 10px; text-align: left;">Username</th>
                    <th style="padding: 10px; text-align: left;">Email</th>
                    <th style="padding: 10px; text-align: left;">Orders</th>
                    <th style="padding: 10px; text-align: left;">Messages</th>
                    <th style="padding: 10px; text-align: left;">Join Date</th>
                    <th style="padding: 10px; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr style="border-bottom: 1px solid #444;">
                        <td style="padding: 10px;"><?= $user['user_id'] ?></td>
                        <td style="padding: 10px;">
                            <?= htmlspecialchars($user['username']) ?>
                            <?php if ($user['username'] === 'admin'): ?>
                                <span style="color: #f44336; font-size: 12px;">(Admin)</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 10px;"><?= htmlspecialchars($user['email']) ?></td>
                        <td style="padding: 10px;"><?= $user['order_count'] ?></td>
                        <td style="padding: 10px;"><?= $user['message_count'] ?></td>
                        <td style="padding: 10px;"><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                        <td style="padding: 10px;">
                            <?php if ($user['username'] !== 'admin'): ?>
                                <a href="<?= BASE_URL ?>index.php?controller=admin&action=editUser&id=<?= $user['user_id'] ?>" 
                                   class="btn" style="padding: 5px 10px; font-size: 14px;">
                                    Edit
                                </a>
                                <a href="<?= BASE_URL ?>index.php?controller=admin&action=deleteUser&id=<?= $user['user_id'] ?>" 
                                   class="btn-delete" 
                                   style="padding: 5px 10px; font-size: 14px;"
                                   onclick="return confirm('Hapus user ini? Semua order dan chat akan ikut terhapus!')">
                                    Delete
                                </a>
                            <?php else: ?>
                                <span style="color: #666; font-size: 14px;">Protected</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <h3>ℹ️ Information</h3>
    <ul style="margin-left: 20px; color: #aaa;">
        <li>Admin account cannot be deleted</li>
        <li>Deleting a user will also delete all their orders, messages, and conversations</li>
        <li>User dapat diedit untuk mengubah username, email, atau password</li>
        <li>Data deletion is permanent and cannot be undone</li>
    </ul>
</div>

<?php include 'views/layout/footer.php'; ?>