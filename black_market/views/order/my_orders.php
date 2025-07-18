<?php include 'views/layout/header.php'; ?>

<h1 style="text-align: center; margin-bottom: 30px;">Order Saya</h1>

<div style="max-width: 800px; margin: 0 auto;">
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

    <!-- Search and Filter Section -->
    <div class="card" style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 15px;">🔍 Filter Order Saya</h3>
        <form method="GET" action="<?= BASE_URL ?>index.php">
            <input type="hidden" name="controller" value="order">
            <input type="hidden" name="action" value="myOrders">
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin-bottom: 10px;">
                <div>
                    <input type="text" 
                           name="search" 
                           placeholder="Cari nama item..." 
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                           style="width: 100%;">
                </div>
                
                <div>
                    <select name="category" style="width: 100%;">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id_categories'] ?>" 
                                    <?= (isset($_GET['category']) && $_GET['category'] == $cat['id_categories']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <select name="order_type" style="width: 100%;">
                        <option value="">Semua Tipe</option>
                        <option value="1" <?= (isset($_GET['order_type']) && $_GET['order_type'] == '1') ? 'selected' : '' ?>>
                            Want to Sell (WTS)
                        </option>
                        <option value="2" <?= (isset($_GET['order_type']) && $_GET['order_type'] == '2') ? 'selected' : '' ?>>
                            Want to Buy (WTB)
                        </option>
                    </select>
                </div>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn" style="padding: 8px 20px;">Filter</button>
                <a href="<?= BASE_URL ?>index.php?controller=order&action=myOrders" class="btn" style="background-color: #666; padding: 8px 20px;">Clear</a>
            </div>
        </form>
    </div>

    <?php if (empty($orders)): ?>
        <div class="card">
            <p style="text-align: center; color: #999;">
                <?= (!empty($_GET['search']) || !empty($_GET['category']) || !empty($_GET['order_type'])) 
                    ? 'Tidak ada order yang sesuai dengan filter' 
                    : 'Anda belum memiliki order' ?>
            </p>
            <div style="text-align: center; margin-top: 20px;">
                <a href="<?= BASE_URL ?>index.php?controller=order&action=create" class="btn">Buat Order Pertama</a>
            </div>
        </div>
    <?php else: ?>
        <p style="text-align: center; margin-bottom: 20px; color: #aaa;">
            Menampilkan <?= count($orders) ?> order
        </p>
        <?php foreach ($orders as $order): ?>
            <div class="card <?= $order['order_type'] == 1 ? 'wts' : 'wtb' ?>">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <h3><?= htmlspecialchars($order['item_name']) ?></h3>
                        <p><small>Kategori: <?= htmlspecialchars($order['category_name']) ?></small></p>
                        <p><strong>Tipe:</strong> <?= htmlspecialchars($order['order_type_name']) ?></p>
                        <p><?= htmlspecialchars($order['description']) ?></p>
                    </div>
                    <div style="text-align: right;">
                        <p><strong>Quantity:</strong> <?= $order['quantity'] ?></p>
                        <p><strong>Harga:</strong> Platinum <?= number_format($order['price'], 0, ',', '.') ?></p>
                        <p><small><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small></p>
                        <div style="margin-top: 10px;">
                            <a href="<?= BASE_URL ?>index.php?controller=order&action=delete&id=<?= $order['order_id'] ?>" 
                               class="btn-delete" 
                               onclick="return confirm('Apakah Anda yakin ingin menghapus order ini?')">
                                Hapus Order
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'views/layout/footer.php'; ?>