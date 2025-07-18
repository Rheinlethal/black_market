<?php include 'views/layout/header.php'; ?>

<h1 style="text-align: center; margin-bottom: 30px;">📋 My Contracts</h1>

<div style="text-align: center; margin-bottom: 20px;">
    <a href="<?= BASE_URL ?>index.php?controller=contract&action=create" class="btn" style="background-color: #4CAF50;">
        + Create New Contract
    </a>
    <a href="<?= BASE_URL ?>index.php?controller=contract" class="btn" style="background-color: #666;">
        Back to All Contracts
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

<?php if (empty($contracts)): ?>
    <div class="card">
        <p style="text-align: center; color: #999;">You haven't created any contracts yet</p>
        <div style="text-align: center; margin-top: 20px;">
            <a href="<?= BASE_URL ?>index.php?controller=contract&action=create" class="btn">
                Create Your First Contract
            </a>
        </div>
    </div>
<?php else: ?>
    <div style="max-width: 1000px; margin: 0 auto;">
        <?php foreach ($contracts as $contract): ?>
            <div class="card" style="margin-bottom: 20px; border-left: 4px solid 
                 <?= $contract['status'] == 'open' ? '#4CAF50' : 
                     ($contract['status'] == 'in_progress' ? '#2196F3' : 
                     ($contract['status'] == 'completed' ? '#9E9E9E' : '#f44336')) ?>;">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div style="flex: 1;">
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                            <h3 style="margin: 0;"><?= htmlspecialchars($contract['title']) ?></h3>
                            <span style="background-color: 
                                 <?= $contract['status'] == 'open' ? '#4CAF50' : 
                                     ($contract['status'] == 'in_progress' ? '#2196F3' : 
                                     ($contract['status'] == 'completed' ? '#9E9E9E' : '#f44336')) ?>; 
                                 color: #fff; padding: 3px 8px; border-radius: 3px; font-size: 12px;">
                                <?= ucfirst(str_replace('_', ' ', $contract['status'])) ?>
                            </span>
                        </div>
                        
                        <p style="color: #aaa; margin-bottom: 10px;">
                            <small>Category: <?= $contract['category_name'] ?: 'No Category' ?> • 
                            Created: <?= date('d/m/Y', strtotime($contract['created_at'])) ?></small>
                        </p>
                        
                        <p style="margin-bottom: 10px;">
                            <?= htmlspecialchars($contract['description']) ?>
                        </p>
                        
                        <?php if ($contract['specifications']): ?>
                            <div style="background-color: #2c2c2c; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                                <strong>Specifications:</strong><br>
                                <pre style="white-space: pre-wrap; margin: 5px 0; font-family: inherit; color: #ccc;">
<?= htmlspecialchars($contract['specifications']) ?>
                                </pre>
                            </div>
                        <?php endif; ?>
                        
                        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
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
                                    <strong>Deadline:</strong> 
                                    <?= date('d/m/Y', strtotime($contract['deadline'])) ?>
                                    <?php if (strtotime($contract['deadline']) < time()): ?>
                                        <span style="color: #f44336;">(Expired)</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($contract['response_count'] > 0): ?>
                                <div>
                                    <strong>Responses:</strong> 
                                    <span style="color: #4CAF50;"><?= $contract['response_count'] ?> messages</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 10px; margin-left: 20px;">
                        <a href="<?= BASE_URL ?>index.php?controller=contract&action=detail&id=<?= $contract['contract_id'] ?>" 
                           class="btn" style="padding: 8px 15px; font-size: 14px;">
                            View
                        </a>
                        
                        <?php if ($contract['status'] == 'open'): ?>
                            <a href="<?= BASE_URL ?>index.php?controller=contract&action=edit&id=<?= $contract['contract_id'] ?>" 
                               class="btn" style="padding: 8px 15px; font-size: 14px; background-color: #FFC107;">
                                Edit
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?= BASE_URL ?>index.php?controller=contract&action=delete&id=<?= $contract['contract_id'] ?>" 
                           class="btn-delete" 
                           style="padding: 8px 15px; font-size: 14px;"
                           onclick="return confirm('Delete this contract?')">
                            Delete
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'views/layout/footer.php'; ?>