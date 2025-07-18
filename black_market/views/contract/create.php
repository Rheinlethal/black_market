<?php include 'views/layout/header.php'; ?>

<div style="max-width: 800px; margin: 0 auto;">
    <div class="card">
        <h1 style="text-align: center; margin-bottom: 30px;">📝 Create New Contract</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="title">Contract Title *</label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       placeholder="e.g., Need Custom Enchanted Sword"
                       required>
            </div>
            
            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" 
                          name="description" 
                          rows="3" 
                          placeholder="Describe what you're looking for..."
                          required></textarea>
            </div>
            
            <div class="form-group">
                <label for="specifications">Detailed Specifications</label>
                <textarea id="specifications" 
                          name="specifications" 
                          rows="5" 
                          placeholder="List your specific requirements:
- Material requirements
- Dimensions/Size
- Quality standards
- Special features
- Etc."></textarea>
            </div>
            
            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id_categories'] ?>">
                            <?= htmlspecialchars($category['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label for="budget_min">Budget Min (Platinum)</label>
                    <input type="number" 
                           id="budget_min" 
                           name="budget_min" 
                           min="0" 
                           step="1"
                           placeholder="Minimum budget">
                </div>
                
                <div class="form-group">
                    <label for="budget_max">Budget Max (Platinum)</label>
                    <input type="number" 
                           id="budget_max" 
                           name="budget_max" 
                           min="0" 
                           step="1"
                           placeholder="Maximum budget">
                </div>
            </div>
            
            <!-- <div class="form-group">
                <label for="deadline">Deadline (Optional)</label>
                <input type="date" 
                       id="deadline" 
                       name="deadline"
                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
            </div> -->
            
            <div style="border-top: 1px solid #444; margin-top: 20px; padding-top: 20px;">
                <h3>Tips for Creating a Good Contract:</h3>
                <ul style="margin-left: 20px; color: #aaa;">
                    <li>Be specific about your requirements</li>
                    <li>Set a realistic budget range</li>
                    <li>Include all important specifications</li>
                    <li>Set a reasonable deadline</li>
                </ul>
            </div>
            
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn" style="flex: 1;">Create Contract</button>
                <a href="<?= BASE_URL ?>index.php?controller=contract" 
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