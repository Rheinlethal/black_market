<?php include 'views/layout/header.php'; ?>

<div style="max-width: 600px; margin: 0 auto;">
    <div class="card">
        <h1 style="text-align: center; margin-bottom: 30px;">Tambah Item Baru</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="item_name">Nama Item</label>
                <input type="text" id="item_name" name="item_name" required>
            </div>
            <div class="form-group">
                <label for="categories_id_categories">Kategori</label>
                <select id="categories_id_categories" name="categories_id_categories" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id_categories'] ?>">
                            <?= htmlspecialchars($category['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <
            </div>
            
            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            
            <button type="submit" style="width: 100%;">Tambah Item</button>
            <a href="<?= BASE_URL ?>index.php?controller=item&action=manage" class="btn" style="width: 100%; text-align: center; margin-top: 10px; background-color: #666;">Batal</a>
        </form>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>