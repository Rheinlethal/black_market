<?php include 'views/layout/header.php'; ?>
<div class="card">
    <h2>📊 Deteksi Anomali Harga</h2>
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top: 20px; background-color: #1a1a1a; border-collapse: collapse;">
        <thead style="background-color: #333;">
            <tr>
                <th style="padding: 10px;">Order ID</th>
                <th>Item</th>
                <th>Harga</th>
                <th>Rata-rata Pasar</th>
                <th>Skor Z</th>
                <th>Persentil Harga</th>
                <th>Username</th>
                <th>Tipe Anomali</th>
                <th>Total Anomali User</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row): ?>
            <tr style="border-bottom: 1px solid #444;">
                <td><?= $row['order_id'] ?></td>
                <td><?= htmlspecialchars($row['item_name']) ?></td>
                <td>Rp<?= number_format($row['price'], 2, ',', '.') ?></td>
                <td>Rp<?= number_format($row['market_avg_price'], 2, ',', '.') ?></td>
                <td style="color: <?= abs($row['price_deviation_score']) > 2 ? '#FF5722' : '#4CAF50' ?>">
                    <?= $row['price_deviation_score'] ?>
                </td>
                <td><?= round($row['price_percentile'] * 100, 2) ?>%</td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><strong><?= $row['anomaly_type'] ?></strong></td>
                <td><?= $row['user_anomaly_count'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'views/layout/footer.php'; ?>