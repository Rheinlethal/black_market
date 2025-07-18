<?php include 'views/layout/header.php'; ?>
<div class="card">
    <h2>🔎 Analisis Jaringan Transaksi (Basis)</h2>
    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top: 20px; background-color: #1a1a1a; border-collapse: collapse;">
        <thead style="background-color: #333;">
            <tr>
                <th>Dari</th>
                <th>Ke</th>
                <th>Jumlah Transaksi</th>
                <th>Total Nilai</th>
                <th>Kedalaman</th>
                <th>Risiko</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row): ?>
            <tr style="border-bottom: 1px solid #444;">
                <td><?= htmlspecialchars($row['username_from']) ?></td>
                <td><?= htmlspecialchars($row['username_to']) ?></td>
                <td><?= $row['transaction_count'] ?></td>
                <td>Rp<?= number_format($row['total_amount'], 2, ',', '.') ?></td>
                <td><?= $row['depth'] ?></td>
                <td>
                    <strong style="
                        color: <?php
                            switch ($row['risk_flag']) {
                                case 'High Frequency Trading': echo '#FFC107'; break;
                                case 'High Value Network': echo '#FF5722'; break;
                                case 'Complex Network': echo '#00BCD4'; break;
                                default: echo '#ccc'; break;
                            }
                        ?>">
                        <?= $row['risk_flag'] ?>
                    </strong>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'views/layout/footer.php'; ?>