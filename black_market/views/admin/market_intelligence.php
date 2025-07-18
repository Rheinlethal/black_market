<?php include 'views/layout/header.php'; ?>
<div class="card">
    <h2>📈 Market Intelligence (24 Jam Terakhir)</h2>
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top: 20px; background-color: #1a1a1a; border-collapse: collapse;">
        <thead style="background-color: #333;">
            <tr>
                <th>📊 Tipe Metrik</th>
                <th>🕒 Waktu / Kategori / Item</th>
                <th>📉 Nilai</th>
                <th>📌 Unit</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr style="border-bottom: 1px solid #444;">
                    <td><?= htmlspecialchars($row['metric_type']) ?></td>
                    <td><?= htmlspecialchars($row['time_period']) ?></td>
                    <td><?= htmlspecialchars($row['value']) ?></td>
                    <td><?= htmlspecialchars($row['unit']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include 'views/layout/footer.php'; ?>