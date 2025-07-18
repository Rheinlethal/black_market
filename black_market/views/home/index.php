<?php include 'views/layout/header.php'; ?>

<h1 style="text-align: center; margin-bottom: 30px;">Black Market - Order Board</h1>

<?php if (isset($open_contracts) && $open_contracts > 0): ?>
<div class="alert alert-success" style="text-align: center; margin-bottom: 20px;">
    📋 Ada <strong><?= $open_contracts ?> contract terbuka</strong> yang mencari supplier! 
    <a href="<?= BASE_URL ?>index.php?controller=contract" style="color: #fff; text-decoration: underline;">Lihat Contracts →</a>
</div>
<?php endif; ?>

<!-- Search and Filter Section -->
<div class="card" style="margin-bottom: 30px;">
    <h3 style="margin-bottom: 20px;">🔍 Search & Filter</h3>
    <form method="GET" action="<?= BASE_URL ?>index.php" id="filterForm">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px;">
            <div>
                <input type="text" 
                       name="search" 
                       placeholder="Cari nama item... (tekan /)" 
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
            
            <div>
                <input type="number" 
                       name="min_price" 
                       placeholder="Harga Min" 
                       value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>"
                       style="width: 100%;"
                       min="0"
                       step="1">
            </div>
            
            <div>
                <input type="number" 
                       name="max_price" 
                       placeholder="Harga Max" 
                       value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>"
                       style="width: 100%;"
                       min="0"
                       step="1">
            </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn">🔍 Filter</button>
            <a href="<?= BASE_URL ?>index.php" class="btn" style="background-color: #666;">Clear Filter</a>
            <a href="<?= BASE_URL ?>index.php?controller=search&action=advanced" class="btn" style="background-color: #2196F3;">
                Advanced Search
            </a>
        </div>
    </form>
</div>

<?php 
$active_filters = !empty($_GET['search']) || !empty($_GET['category']) || 
                  !empty($_GET['order_type']) || !empty($_GET['min_price']) || !empty($_GET['max_price']);

if ($active_filters): 
?>
    <div class="alert alert-success" style="text-align: center;">
        Filter aktif - Menampilkan <?= count($wts_orders) + count($wtb_orders) ?> hasil
    </div>
<?php endif; ?>

<div class="order-grid">
    <div class="order-section">
        <h2>🛒 Want to Buy (WTB)</h2>
        <?php if (empty($wtb_orders)): ?>
            <p style="text-align: center; color: #999;">
                <?= $active_filters ? 'Tidak ada hasil untuk filter ini' : 'Belum ada order WTB' ?>
            </p>
        <?php else: ?>
            <?php foreach ($wtb_orders as $order): ?>
                <div class="order-item wtb">
                    <h3><?= htmlspecialchars($order['item_name']) ?></h3>
                    <p><small>Kategori: <?= htmlspecialchars($order['category_name']) ?></small></p>
                    <p><?= htmlspecialchars($order['description']) ?></p>
                    <div class="order-details">
                        <div>
                            <strong>Quantity:</strong> <?= $order['quantity'] ?><br>
                            <strong>Platinum :</strong> <?= number_format($order['price'], 0, ',', '.') ?>
                        </div>
                        <div style="text-align: right;">
                            <small>Oleh: <?= htmlspecialchars($order['username']) ?></small><br>
                            <small><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small>
                            <?php if ($order['user_id'] != $_SESSION['user_id']): ?>
                                <div style="margin-top: 10px;">
                                    <a href="<?= BASE_URL ?>index.php?controller=chat&action=startChat&user_id=<?= $order['user_id'] ?>&order_id=<?= $order['order_id'] ?>" 
                                       class="btn-chat">
                                        💬 Chat
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="order-section">
        <h2>💰 Want to Sell (WTS)</h2>
        <?php if (empty($wts_orders)): ?>
            <p style="text-align: center; color: #999;">
                <?= $active_filters ? 'Tidak ada hasil untuk filter ini' : 'Belum ada order WTS' ?>
            </p>
        <?php else: ?>
            <?php foreach ($wts_orders as $order): ?>
                <div class="order-item wts">
                    <h3><?= htmlspecialchars($order['item_name']) ?></h3>
                    <p><small>Kategori: <?= htmlspecialchars($order['category_name']) ?></small></p>
                    <p><?= htmlspecialchars($order['description']) ?></p>
                    <div class="order-details">
                        <div>
                            <strong>Quantity:</strong> <?= $order['quantity'] ?><br>
                            <strong>Platinum :</strong> <?= number_format($order['price'], 0, ',', '.') ?>
                        </div>
                        <div style="text-align: right;">
                            <small>Oleh: <?= htmlspecialchars($order['username']) ?></small><br>
                            <small><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small>
                            <?php if ($order['user_id'] != $_SESSION['user_id']): ?>
                                <div style="margin-top: 10px;">
                                    <a href="<?= BASE_URL ?>index.php?controller=chat&action=startChat&user_id=<?= $order['user_id'] ?>&order_id=<?= $order['order_id'] ?>" 
                                       class="btn-chat">
                                        💬 Chat
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>