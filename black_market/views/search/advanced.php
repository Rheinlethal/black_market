<?php include 'views/layout/header.php'; ?>

<h1 style="text-align: center; margin-bottom: 30px;">🔍 Advanced Search</h1>

<!-- Search Form -->
<div class="card" style="margin-bottom: 30px;">
    <form method="GET" action="<?= BASE_URL ?>index.php">
        <input type="hidden" name="controller" value="search">
        <input type="hidden" name="action" value="advanced">
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-bottom: 20px;">
            <div>
                <label for="search">Nama/Deskripsi Item:</label>
                <input type="text" 
                       id="search"
                       name="search" 
                       placeholder="Cari item..." 
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            
            <div>
                <label for="category">Kategori:</label>
                <select id="category" name="category">
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
                <label for="order_type">Tipe Order:</label>
                <select id="order_type" name="order_type">
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
                <label for="username">Username:</label>
                <input type="text" 
                       id="username"
                       name="username" 
                       placeholder="Cari user..." 
                       value="<?= htmlspecialchars($_GET['username'] ?? '') ?>">
            </div>
            
            <div>
                <label for="min_price">Harga Min:</label>
                <input type="number" 
                       id="min_price"
                       name="min_price" 
                       placeholder="0" 
                       value="<?= htmlspecialchars($_GET['min_price'] ?? '') ?>"
                       min="0"
                       step="1">
            </div>
            
            <div>
                <label for="max_price">Harga Max:</label>
                <input type="number" 
                       id="max_price"
                       name="max_price" 
                       placeholder="999999999" 
                       value="<?= htmlspecialchars($_GET['max_price'] ?? '') ?>"
                       min="0"
                       step="1">
            </div>
            
            <div>
                <label for="sort_by">Urutkan:</label>
                <select id="sort_by" name="sort_by">
                    <option value="created_at" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'created_at') ? 'selected' : '' ?>>
                        Tanggal
                    </option>
                    <option value="price" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'price') ? 'selected' : '' ?>>
                        Harga
                    </option>
                    <option value="item_name" <?= (isset($_GET['sort_by']) && $_GET['sort_by'] == 'item_name') ? 'selected' : '' ?>>
                        Nama Item
                    </option>
                </select>
            </div>
            
            <div>
                <label for="sort_order">Urutan:</label>
                <select id="sort_order" name="sort_order">
                    <option value="DESC" <?= (isset($_GET['sort_order']) && $_GET['sort_order'] == 'DESC') ? 'selected' : '' ?>>
                        Menurun
                    </option>
                    <option value="ASC" <?= (isset($_GET['sort_order']) && $_GET['sort_order'] == 'ASC') ? 'selected' : '' ?>>
                        Menaik
                    </option>
                </select>
            </div>
        </div>
        
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button type="submit" class="btn">🔍 Search</button>
            <a href="<?= BASE_URL ?>index.php?controller=search&action=advanced" class="btn" style="background-color: #666;">Reset</a>
            <a href="<?= BASE_URL ?>index.php" class="btn" style="background-color: #888;">Kembali</a>
        </div>
    </form>
</div>

<!-- Search Results -->
<?php if (isset($_GET['search']) || isset($_GET['category']) || isset($_GET['order_type']) || 
          isset($_GET['min_price']) || isset($_GET['max_price']) || isset($_GET['username'])): ?>
    
    <!-- Statistics -->
    <div class="card" style="margin-bottom: 20px; background-color: #3c3c3c;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; text-align: center;">
            <div>
                <h3 style="color: #4CAF50; margin-bottom: 5px;"><?= $total_orders ?></h3>
                <p>Total Order</p>
            </div>
            <div>
                <h3 style="color: #2196F3; margin-bottom: 5px;">Rp <?= number_format($total_value, 0, ',', '.') ?></h3>
                <p>Total Nilai</p>
            </div>
            <div>
                <h3 style="color: #FFC107; margin-bottom: 5px;">Rp <?= number_format($avg_price, 0, ',', '.') ?></h3>
                <p>Rata-rata Harga</p>
            </div>
        </div>
    </div>
    
    <!-- Results List -->
    <?php if (empty($orders)): ?>
        <div class="card">
            <p style="text-align: center; color: #999;">Tidak ada hasil yang sesuai dengan pencarian</p>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="card" style="margin-bottom: 15px;">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <span class="<?= $order['order_type'] == 1 ? 'wts' : 'wtb' ?>" 
                                  style="padding: 5px 10px; border-radius: 5px; font-size: 14px;">
                                <?= $order['order_type_name'] ?>
                            </span>
                            <h3 style="margin: 0;"><?= htmlspecialchars($order['item_name']) ?></h3>
                        </div>
                        
                        <p style="margin: 5px 0;">
                            <small>Kategori: <?= htmlspecialchars($order['category_name']) ?></small> | 
                            <small>Oleh: <strong><?= htmlspecialchars($order['username']) ?></strong></small>
                        </p>
                        
                        <p style="color: #ccc; margin: 10px 0;">
                            <?= htmlspecialchars(substr($order['description'], 0, 150)) ?>...
                        </p>
                        
                        <div style="display: flex; gap: 20px; margin-top: 10px;">
                            <p><strong>Quantity:</strong> <?= $order['quantity'] ?></p>
                            <p><strong>Harga:</strong> <span style="color: #4CAF50;">Rp <?= number_format($order['price'], 0, ',', '.') ?></span></p>
                            <p><small><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small></p>
                        </div>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <?php if ($order['user_id'] != $_SESSION['user_id']): ?>
                            <a href="<?= BASE_URL ?>index.php?controller=chat&action=startChat&user_id=<?= $order['user_id'] ?>&order_id=<?= $order['order_id'] ?>" 
                               class="btn-chat">
                                💬 Chat
                            </a>
                        <?php else: ?>
                            <span style="color: #666; font-size: 14px;">Order Anda</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
<?php endif; ?>

<?php include 'views/layout/footer.php'; ?>