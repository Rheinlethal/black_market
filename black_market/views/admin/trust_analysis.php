<?php include 'views/layout/header.php'; ?>
<div class="card">
    <h2>🛡️ Analisis Kepercayaan Pengguna</h2>
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top: 20px; background-color: #1a1a1a; border-collapse: collapse;">
        <thead style="background-color: #333;">
            <tr>
                <th>Username</th>
                <th>Order</th>
                <th>Jual</th>
                <th>Beli</th>
                <th>Pesan</th>
                <th>Chat Aktif</th>
                <th>Trust Score</th>
                <th>Kategori</th>
                <th>Tipe User</th>
                <th>Ranking</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row): ?>
            <tr style="border-bottom: 1px solid #444;">
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= $row['total_orders'] ?></td>
                <td><?= $row['sell_orders'] ?></td>
                <td><?= $row['buy_orders'] ?></td>
                <td><?= $row['messages_sent'] ?></td>
                <td><?= $row['active_conversations'] ?></td>
                <td><?= number_format($row['trust_score'], 2) ?></td>
                <td><strong><?= $row['trust_category'] ?></strong></td>
                <td><?= $row['user_type'] ?></td>
                <td>#<?= $row['trust_rank'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'views/layout/footer.php'; ?>