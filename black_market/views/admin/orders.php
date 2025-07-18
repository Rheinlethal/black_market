<?php include 'views/layout/header.php'; ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <h1>📦 Order Management</h1>
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

<!-- Filter Section -->
<div class="card" style="margin-bottom: 20px;">
    <h3 style="margin-bottom: 15px;">🔍 Filter Orders</h3>
    <form method="GET" action="<?= BASE_URL ?>index.php">
        <input type="hidden" name="controller" value="admin">
        <input type="hidden" name="action" value="orders">
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; margin-bottom: 10px;">
            <div>
                <input type="text" 
                       name="search" 
                       placeholder="Search item..." 
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            
            <div>
                <select name="order_type">
                    <option value="">All Types</option>
                    <option value="1" <?= (isset($_GET['order_type']) && $_GET['order_type'] == '1') ? 'selected' : '' ?>>
                        Want to Sell (WTS)
                    </option>
                    <option value="2" <?= (isset($_GET['order_type']) && $_GET['order_type'] == '2') ? 'selected' : '' ?>>
                        Want to Buy (WTB)
                    </option>
                </select>
            </div>
            
            <div>
                <input type="text" 
                       name="username" 
                       placeholder="Username..." 
                       value="<?= htmlspecialchars($_GET['username'] ?? '') ?>">
            </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn" style="padding: 8px 20px;">Filter</button>
            <a href="<?= BASE_URL ?>index.php?controller=admin&action=orders" 
               class="btn" style="background-color: #666; padding: 8px 20px;">Clear</a>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #555;">
                    <th style="padding: 10px; text-align: left;">ID</th>
                    <th style="padding: 10px; text-align: left;">User</th>
                    <th style="padding: 10px; text-align: left;">Item</th>
                    <th style="padding: 10px; text-align: left;">Category</th>
                    <th style="padding: 10px; text-align: left;">Type</th>
                    <th style="padding: 10px; text-align: left;">Qty</th>
                    <th style="padding: 10px; text-align: left;">Price</th>
                    <th style="padding: 10px; text-align: left;">Total</th>
                    <th style="padding: 10px; text-align: left;">Date</th>
                    <th style="padding: 10px; text-align: left;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="10" style="padding: 20px; text-align: center; color: #999;">
                            No orders found
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr style="border-bottom: 1px solid #444;">
                            <td style="padding: 10px;">#<?= $order['order_id'] ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($order['username']) ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($order['item_name']) ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($order['category_name']) ?></td>
                            <td style="padding: 10px;">
                                <span class="<?= $order['order_type'] == 1 ? 'wts' : 'wtb' ?>" 
                                      style="padding: 3px 8px; border-radius: 3px; font-size: 12px;">
                                    <?= $order['order_type_name'] ?>
                                </span>
                            </td>
                            <td style="padding: 10px;"><?= $order['quantity'] ?></td>
                            <td style="padding: 10px;">Rp <?= number_format($order['price'], 0, ',', '.') ?></td>
                            <td style="padding: 10px;">
                                <strong>Rp <?= number_format($order['price'] * $order['quantity'], 0, ',', '.') ?></strong>
                            </td>
                            <td style="padding: 10px;"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                            <td style="padding: 10px;">
                                <a href="<?= BASE_URL ?>index.php?controller=admin&action=deleteOrder&id=<?= $order['order_id'] ?>" 
                                   class="btn-delete" 
                                   style="padding: 5px 10px; font-size: 14px;"
                                   onclick="return confirm('Delete this order?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if (!empty($orders)): ?>
        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #444;">
            <p style="text-align: center; color: #aaa;">
                Total: <?= count($orders) ?> orders | 
                Total Value: Rp <?= number_format(array_sum(array_map(function($o) { 
                    return $o['price'] * $o['quantity']; 
                }, $orders)), 0, ',', '.') ?>
            </p>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/layout/footer.php'; ?>