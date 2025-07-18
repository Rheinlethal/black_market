<?php include 'views/layout/header.php'; ?>

<h1 style="text-align: center; margin-bottom: 30px;">Kelola Item</h1>

<div style="text-align: center; margin-bottom: 20px;">
    <a href="<?= BASE_URL ?>index.php?controller=item&action=add" class="btn">+ Tambah Item Baru</a>
</div>

<div style="max-width: 1000px; margin: 0 auto;">
    <?php if (empty($items)): ?>
        <div class="card">
            <p style="text-align: center; color: #999;">Belum ada item</p>
        </div>
    <?php else: ?>
        <div class="card">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #555;">
                        <th style="padding: 10px; text-align: left;">ID</th>
                        <th style="padding: 10px; text-align: left;">Nama Item</th>
                        <th style="padding: 10px; text-align: left;">Kategori</th>
                        <th style="padding: 10px; text-align: left;">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr style="border-bottom: 1px solid #444;">
                            <td style="padding: 10px;"><?= $item['iditem'] ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($item['item_name']) ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars($item['category_name']) ?></td>
                            <td style="padding: 10px;"><?= htmlspecialchars(substr($item['description'], 0, 50)) ?>...</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/layout/footer.php'; ?>