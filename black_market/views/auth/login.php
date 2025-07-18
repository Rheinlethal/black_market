<?php include 'views/layout/header.php'; ?>

<div class="login-container">
    <div class="card">
        <h1 style="text-align: center; margin-bottom: 30px;">Black Market Login</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" style="width: 100%;">Login</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            Belum punya akun? <a href="<?= BASE_URL ?>index.php?controller=auth&action=register" style="color: #4CAF50;">Daftar di sini</a>
        </p>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>