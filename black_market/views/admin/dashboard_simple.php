<?php include 'views/layout/header.php'; ?>

<h1 style="text-align: center; margin-bottom: 30px;">🛡️ Admin Dashboard</h1>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
    <div class="card" style="background-color: #1976D2;">
        <h2>Users</h2>
        <p>Manage users</p>
        <a href="<?= BASE_URL ?>index.php?controller=admin&action=users" class="btn">
            Manage →
        </a>
    </div>
    
    <div class="card" style="background-color: #388E3C;">
        <h2>Orders</h2>
        <p>Manage orders</p>
        <a href="<?= BASE_URL ?>index.php?controller=admin&action=orders" class="btn">
            Manage →
        </a>
    </div>
    
    <div class="card" style="background-color: #F57C00;">
        <h2>Items</h2>
        <p>Manage items</p>
        <a href="<?= BASE_URL ?>index.php?controller=item&action=manage" class="btn">
            Manage →
        </a>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <h2>Quick Actions</h2>
    <a href="<?= BASE_URL ?>index.php" class="btn" style="background-color: #666;">
        Back to Market
    </a>
</div>

<?php include 'views/layout/footer.php'; ?>