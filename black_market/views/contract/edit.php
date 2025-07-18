<?php include 'views/layout/header.php'; ?>

<div style="max-width: 800px; margin: 0 auto;">
    <div class="card">
        <h1 style="text-align: center; margin-bottom: 30px;">✏️ Edit Contract</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="title">Contract Title *</label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="<?= htmlspecialchars($contract_data['title']) ?>"
                       required>
            </div>
            
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" 
                          name="description" 
                          rows="3" 
                          required><?= htmlspecialchars($contract_data['description']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="specifications">Detailed Specifications</label>
                <textarea id="specifications" 
                          name="specifications" 
                          rows="5"><?= htmlspecialchars($contract_data['specifications']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id_categories'] ?>"
                                <?= $contract_data['category_id'] == $category['id_categories'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="budget_min">Budget Min (Rp)</label>
                    <input type="number" 
                           id="budget_min" 
                           name="budget_min" 
                           min="0" 
                           step="1"
                           value="<?= $contract_data['budget_min'] ?>">
                </div>
                
                <div class="form-group">
                    <label for="budget_max">Budget Max (Rp)</label>
                    <input type="number" 
                           id="budget_max" 
                           name="budget_max" 
                           min="0" 
                           step="1"
                           value="<?= $contract_data['budget_max'] ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="deadline">Deadline</label>
                <input type="date" 
                       id="deadline" 
                       name="deadline"
                       value="<?= $contract_data['deadline'] ?>">
            </div>
            
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="open" <?= $contract_data['status'] == 'open' ? 'selected' : '' ?>>
                        Open
                    </option>
                    <option value="in_progress" <?= $contract_data['status'] == 'in_progress' ? 'selected' : '' ?>>
                        In Progress
                    </option>
                    <option value="completed" <?= $contract_data['status'] == 'completed' ? 'selected' : '' ?>>
                        Completed
                    </option>
                    <option value="cancelled" <?= $contract_data['status'] == 'cancelled' ? 'selected' : '' ?>>
                        Cancelled
                    </option>
                </select>
            </div>
            
            <div style="border-top: 1px solid #444; margin-top: 20px; padding-top: 20px;">
                <p style="color: #aaa;">
                    <strong>Note:</strong> Changing status to "Completed" or "Cancelled" will close the contract.
                </p>
            </div>
            
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn" style="flex: 1;">Update Contract</button>
                <a href="<?= BASE_URL ?>index.php?controller=contract&action=myContracts" 
                   class="btn" style="flex: 1; text-align: center; background-color: #666;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
// Validate budget
document.querySelector('form').addEventListener('submit', function(e) {
    const budgetMin = parseFloat(document.getElementById('budget_min').value) || 0;
    const budgetMax = parseFloat(document.getElementById('budget_max').value) || 0;
    
    if (budgetMin > 0 && budgetMax > 0 && budgetMin > budgetMax) {
        e.preventDefault();
        alert('Budget minimum cannot be greater than budget maximum!');
        return false;
    }
});
</script>

<?php include 'views/layout/footer.php'; ?>