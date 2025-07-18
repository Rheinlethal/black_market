<?php include 'views/layout/header.php'; ?>

<h1 style="text-align: center; margin-bottom: 30px;">🛡️ Admin Dashboard</h1>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="card" style="background-color: #1976D2;">
        <h2><?= $total_users ?></h2>
        <p>Total Users</p>
        <a href="<?= BASE_URL ?>index.php?controller=admin&action=users" class="btn" style="margin-top: 10px;">
            Kelola Users →
        </a>
    </div>
    
    <div class="card" style="background-color: #388E3C;">
        <h2><?= $total_orders ?></h2>
        <p>Total Orders</p>
        <a href="<?= BASE_URL ?>index.php?controller=admin&action=orders" class="btn" style="margin-top: 10px;">
            Kelola Orders →
        </a>
    </div>
    
    <div class="card" style="background-color: #F57C00;">
        <h2><?= $total_items ?></h2>
        <p>Total Items</p>
        <a href="<?= BASE_URL ?>index.php?controller=item&action=manage" class="btn" style="margin-top: 10px;">
            Kelola Items →
        </a>
    </div>
    
    <div class="card" style="background-color: #7B1FA2;">
        <h2><?= $total_contracts ?? 0 ?></h2>
        <p>Open Contracts</p>
        <a href="<?= BASE_URL ?>index.php?controller=contract" class="btn" style="margin-top: 10px;">
            View Contracts →
        </a>
    </div>
    
    <div class="card" style="background-color: #C2185B;">
        <h2>Rp <?= number_format($total_value, 0, ',', '.') ?></h2>
        <p>Total Transaction Value</p>
    </div>

    <li><a href="index.php?controller=admin&action=trustAnalysis">
    🛡️ Trust Analysis
</a></li>

<li><a href="index.php?controller=admin&action=marketIntelligence">
    📈 Market Intelligence
</a></li>

                    <li><a href="index.php?controller=admin&action=suspiciousNetwork">
                        🧠 Analisis Jaringan (Basis)
                    </a></li>

                    <li><a href="index.php?controller=admin&action=priceAnomalyDetection">
                    📊 Deteksi Anomali Harga
                    </a></li>


</div>

<!-- Recent Orders -->
<div class="card">
    <h2 style="margin-bottom: 20px;">📋 Recent Orders</h2>
    
    <?php if (empty($recent_orders)): ?>
        <p style="text-align: center; color: #999;">Belum ada order</p>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #555;">
                        <th style="padding: 10px; text-align: left;">Order ID</th>
                        <th style="padding: 10px; text-align: left;">User</th>
                        <th style="padding: 10px; text-align: left;">Item</th>
                        <th style="padding: 10px; text-align: left;">Type</th>
                        <th style="padding: 10px; text-align: left;">Qty</th>
                        <th style="padding: 10px; text-align: left;">Price</th>
                        <th style="padding: 10px; text-align: left;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_orders as $order): ?>
                        <tr style="border-bottom: 1px solid #444;">
                            <td style="padding: 10px;">#<?= $order['order_id'] ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($order['username']) ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($order['item_name']) ?></td>
                            <td style="padding: 10px;">
                                <span class="<?= $order['order_type'] == 1 ? 'wts' : 'wtb' ?>" 
                                      style="padding: 3px 8px; border-radius: 3px; font-size: 12px;">
                                    <?= $order['order_type_name'] ?>
                                </span>
                            </td>
                            <td style="padding: 10px;"><?= $order['quantity'] ?></td>
                            <td style="padding: 10px;">Platinum <?= number_format($order['price'], 0, ',', '.') ?></td>
                            <td style="padding: 10px;"><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=orders" class="btn">
                Lihat Semua Orders →
            </a>
        </div>
    <?php endif; ?>
</div>

<!-- Quick Actions -->
<div class="card" style="margin-top: 20px;">
    <h2 style="margin-bottom: 20px;">⚡ Quick Actions</h2>
    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
        <a href="<?= BASE_URL ?>index.php?controller=item&action=add" class="btn">+ Tambah Item</a>
        <a href="<?= BASE_URL ?>index.php?controller=admin&action=users" class="btn" style="background-color: #1976D2;">Manage Users</a>
        <a href="<?= BASE_URL ?>index.php?controller=admin&action=orders" class="btn" style="background-color: #388E3C;">Manage Orders</a>
        <a href="<?= BASE_URL ?>index.php" class="btn" style="background-color: #666;">Back to Market</a>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>