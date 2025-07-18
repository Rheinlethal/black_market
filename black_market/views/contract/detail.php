<?php include 'views/layout/header.php'; ?>

<div style="max-width: 800px; margin: 0 auto;">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 style="margin: 0;">Contract Details</h1>
            <a href="<?= BASE_URL ?>index.php?controller=contract" class="btn" style="background-color: #666;">
                ← Back
            </a>
        </div>
        
        <div style="border-bottom: 1px solid #444; padding-bottom: 20px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <h2 style="margin: 0;"><?= htmlspecialchars($contract_data['title']) ?></h2>
                <span style="background-color: 
                     <?= $contract_data['status'] == 'open' ? '#4CAF50' : 
                         ($contract_data['status'] == 'in_progress' ? '#2196F3' : 
                         ($contract_data['status'] == 'completed' ? '#9E9E9E' : '#f44336')) ?>; 
                     color: #fff; padding: 5px 10px; border-radius: 5px;">
                    <?= ucfirst(str_replace('_', ' ', $contract_data['status'])) ?>
                </span>
            </div>
            
            <p style="color: #aaa; margin-top: 10px;">
                Posted by: <strong><?= htmlspecialchars($contract_data['username']) ?></strong> • 
                Category: <?= htmlspecialchars($contract_data['category_name'] ?: 'No Category') ?> • 
                Posted: <?= date('d/m/Y H:i', strtotime($contract_data['created_at'])) ?>
            </p>
        </div>
        
        <div style="margin-bottom: 20px;">
            <h3>Description</h3>
            <p style="color: #ccc; line-height: 1.6;">
                <?= nl2br(htmlspecialchars($contract_data['description'])) ?>
            </p>
        </div>
        
        <?php if ($contract_data['specifications']): ?>
            <div style="margin-bottom: 20px;">
                <h3>Specifications</h3>
                <div style="background-color: #2c2c2c; padding: 15px; border-radius: 5px;">
                    <pre style="white-space: pre-wrap; margin: 0; font-family: inherit; color: #ccc;">
<?= htmlspecialchars($contract_data['specifications']) ?>
                    </pre>
                </div>
            </div>
        <?php endif; ?>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <div class="card" style="background-color: #3c3c3c;">
                <h4 style="margin-bottom: 10px;">💰 Budget Range</h4>
                <?php if ($contract_data['budget_min'] || $contract_data['budget_max']): ?>
                    <p style="font-size: 18px; color: #4CAF50;">
                        Rp <?= number_format($contract_data['budget_min'] ?: 0, 0, ',', '.') ?> - 
                        Rp <?= number_format($contract_data['budget_max'] ?: 0, 0, ',', '.') ?>
                    </p>
                <?php else: ?>
                    <p style="font-size: 18px; color: #FFC107;">Negotiable</p>
                <?php endif; ?>
            </div>
            
            <?php if ($contract_data['deadline']): ?>
                <div class="card" style="background-color: #3c3c3c;">
                    <h4 style="margin-bottom: 10px;">📅 Deadline</h4>
                    <p style="font-size: 18px; color: <?= strtotime($contract_data['deadline']) < time() ? '#f44336' : '#2196F3' ?>;">
                        <?= date('d/m/Y', strtotime($contract_data['deadline'])) ?>
                        <?php if (strtotime($contract_data['deadline']) < time()): ?>
                            <br><small>(Expired)</small>
                        <?php else: ?>
                            <br><small>(<?= ceil((strtotime($contract_data['deadline']) - time()) / 86400) ?> days left)</small>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Action Buttons -->
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <?php if ($contract_data['user_id'] == $_SESSION['user_id']): ?>
                <!-- Owner Actions -->
                <?php if ($contract_data['status'] == 'open'): ?>
                    <a href="<?= BASE_URL ?>index.php?controller=contract&action=edit&id=<?= $contract_data['contract_id'] ?>" 
                       class="btn" style="background-color: #FFC107;">
                        ✏️ Edit Contract
                    </a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>index.php?controller=contract&action=myContracts" 
                   class="btn" style="background-color: #2196F3;">
                    📋 My Contracts
                </a>
            <?php else: ?>
                <!-- Visitor Actions -->
                <a href="<?= BASE_URL ?>index.php?controller=contract&action=contactOwner&id=<?= $contract_data['contract_id'] ?>" 
                   class="btn" style="background-color: #4CAF50;">
                    💬 Contact Contract Owner
                </a>
                <a href="<?= BASE_URL ?>index.php?controller=chat&action=startChat&user_id=<?= $contract_data['user_id'] ?>" 
                   class="btn" style="background-color: #2196F3;">
                    💬 Direct Message
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Additional Info -->
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #444;">
            <p style="color: #666; font-size: 14px;">
                Contract ID: #<?= $contract_data['contract_id'] ?> • 
                Last Updated: <?= $contract_data['updated_at'] ? date('d/m/Y H:i', strtotime($contract_data['updated_at'])) : 'Never' ?>
            </p>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>