<?php include 'views/layout/header.php'; ?>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <h1 style="text-align: center; margin-bottom: 30px;">Buat Order Baru</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="order_type">Tipe Order</label>
                <select id="order_type" name="order_type" required>
                    <option value="">-- Pilih Tipe Order --</option>
                    <option value="1">Want to Sell (WTS)</option>
                    <option value="2">Want to Buy (WTB)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="iditem">Item</label>
                <select id="iditem" name="iditem" required>
                    <option value="">-- Pilih Item --</option>
                    <?php foreach ($items as $item): ?>
                        <option value="<?= $item['iditem'] ?>">
                            <?= htmlspecialchars($item['item_name']) ?> - <?= htmlspecialchars($item['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" min="1" required>
            </div>
            
            <div class="form-group">
                <label for="price">Harga per Item (Platinum)</label>
                <input type="number" id="price" name="price" min="0" step="1" required>
            </div>
            
            <button type="submit" style="width: 100%;">Buat Order</button>
            <a href="<?= BASE_URL ?>index.php" class="btn" style="width: 100%; text-align: center; margin-top: 10px; background-color: #666;">Batal</a>
        </form>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>