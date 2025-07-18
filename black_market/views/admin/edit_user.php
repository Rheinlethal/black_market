<?php include 'views/layout/header.php'; ?>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <h1 style="text-align: center; margin-bottom: 30px;">Edit User</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="<?= htmlspecialchars($user['username']) ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="<?= htmlspecialchars($user['email']) ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password (kosongkan jika tidak ingin mengubah)</label>
                <input type="password" 
                       id="new_password" 
                       name="new_password">
            </div>
            
            <div style="border-top: 1px solid #444; margin-top: 20px; padding-top: 20px;">
                <h4>User Information:</h4>
                <p>User ID: <?= $user['user_id'] ?></p>
                <p>Created: <?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></p>
            </div>
            
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn" style="flex: 1;">Update User</button>
                <a href="<?= BASE_URL ?>index.php?controller=admin&action=users" 
                   class="btn" style="flex: 1; text-align: center; background-color: #666;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>