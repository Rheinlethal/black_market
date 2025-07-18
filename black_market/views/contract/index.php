<?php include 'views/layout/header.php'; ?>

<h1 style="text-align: center; margin-bottom: 30px;">📋 Contract Marketplace</h1>

<!-- Filter Section -->
<div class="card" style="margin-bottom: 30px;">
    <h3 style="margin-bottom: 20px;">🔍 Search Contracts</h3>
    <form method="GET" action="<?= BASE_URL ?>index.php" id="filterForm">
        <input type="hidden" name="controller" value="contract">
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px;">
            <div>
                <input type="text" 
                       name="search" 
                       placeholder="Search contracts..." 
                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                       style="width: 100%;">
            </div>
            
            <div>
                <select name="category" style="width: 100%;">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id_categories'] ?>" 
                                <?= (isset($_GET['category']) && $_GET['category'] == $cat['id_categories']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div>
                <select name="status" style="width: 100%;">
                    <option value="">All Status</option>
                    <option value="open" <?= (isset($_GET['status']) && $_GET['status'] == 'open') ? 'selected' : '' ?>>
                        Open
                    </option>
                    <option value="in_progress" <?= (isset($_GET['status']) && $_GET['status'] == 'in_progress') ? 'selected' : '' ?>>
                        In Progress
                    </option>
                    <option value="completed" <?= (isset($_GET['status']) && $_GET['status'] == 'completed') ? 'selected' : '' ?>>
                        Completed
                    </option>
                </select>
            </div>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn">🔍 Filter</button>
            <a href="<?= BASE_URL ?>index.php?controller=contract" class="btn" style="background-color: #666;">Clear</a>
            <a href="<?= BASE_URL ?>index.php?controller=contract&action=create" class="btn" style="background-color: #4CAF50;">
                + Create Contract
            </a>
            <a href="<?= BASE_URL ?>index.php?controller=contract&action=myContracts" class="btn" style="background-color: #2196F3;">
                My Contracts
            </a>
        </div>
    </form>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success'] ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<!-- Contract List -->
<?php if (empty($contracts)): ?>
    <div class="card">
        <p style="text-align: center; color: #999;">No contracts found</p>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px;">
        <?php foreach ($contracts as $contract): ?>
            <div class="card" style="border-left: 4px solid <?= $contract['status'] == 'open' ? '#4CAF50' : '#FFC107' ?>;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                    <h3 style="margin: 0;"><?= htmlspecialchars($contract['title']) ?></h3>
                    <span style="background-color: <?= $contract['status'] == 'open' ? '#4CAF50' : '#FFC107' ?>; 
                                 color: #fff; padding: 3px 8px; border-radius: 3px; font-size: 12px;">
                        <?= ucfirst($contract['status']) ?>
                    </span>
                </div>
                
                <p style="color: #aaa; margin-bottom: 10px;">
                    <small>by <?= htmlspecialchars($contract['username']) ?> • 
                    <?= $contract['category_name'] ? htmlspecialchars($contract['category_name']) : 'No Category' ?></small>
                </p>
                
                <p style="margin-bottom: 15px;">
                    <?= htmlspecialchars(substr($contract['description'], 0, 150)) ?>...
                </p>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <div>
                        <strong>Budget:</strong> 
                        <?php if ($contract['budget_min'] || $contract['budget_max']): ?>
                            Rp <?= number_format($contract['budget_min'] ?: 0, 0, ',', '.') ?> - 
                            Rp <?= number_format($contract['budget_max'] ?: 0, 0, ',', '.') ?>
                        <?php else: ?>
                            <span style="color: #999;">Negotiable</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($contract['deadline']): ?>
                        <div>
                            <small>Deadline: <?= date('d/m/Y', strtotime($contract['deadline'])) ?></small>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <a href="<?= BASE_URL ?>index.php?controller=contract&action=detail&id=<?= $contract['contract_id'] ?>" 
                       class="btn" style="flex: 1; text-align: center; padding: 8px;">
                        View Details
                    </a>
                    <?php if ($contract['user_id'] != $_SESSION['user_id']): ?>
                        <a href="<?= BASE_URL ?>index.php?controller=contract&action=contactOwner&id=<?= $contract['contract_id'] ?>" 
                           class="btn-chat" style="flex: 1; text-align: center; padding: 8px;">
                            💬 Contact
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'views/layout/footer.php'; ?>