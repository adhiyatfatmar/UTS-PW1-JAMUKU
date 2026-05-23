<?php
session_start();
require 'db.php';

$racikans = $db->query("SELECT * FROM racikan ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jamuku - Daftar Racikan</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; background: #fffbf0; }
        h1 { color: #7a4f01; }
        .racikan-card { background: white; border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin-bottom: 16px; }
        .racikan-card h3 { margin: 0 0 10px; color: #c8860a; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background: #f5e6c8; }
        .total { font-weight: bold; color: #c8860a; margin-top: 8px; }
        .nav a { background: #555; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; margin-right: 8px; }
        .kosong { text-align: center; color: #888; padding: 40px; }
        .btn { display: inline-block; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 14px; border: none; cursor: pointer; margin-right: 6px; margin-top: 10px; }
        .btn-load { background: #2e7d32; color: white; }
        .btn-edit { background: #c8860a; color: white; }
        .btn-hapus { background: #c0392b; color: white; }
    </style>
</head>
<body>
    <h1>📋 Daftar Racikan Tersimpan</h1>
    <div class="nav" style="margin-bottom:20px">
        <a href="index.php">🌿 Racik Baru</a>
        <a href="keranjang.php">🛒 Keranjang</a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <p style="color:green;font-weight:bold"><?= htmlspecialchars($_GET['msg']) ?></p>
    <?php endif; ?>

    <?php if (empty($racikans)): ?>
        <p class="kosong">Belum ada racikan tersimpan.</p>
    <?php else: ?>
        <?php foreach ($racikans as $r): ?>
        <div class="racikan-card">
            <h3>🌿 <?= htmlspecialchars($r['nama_racikan']) ?></h3>
            <small>Disimpan: <?= $r['created_at'] ?></small>
            <table style="margin-top:10px">
                <thead>
                    <tr><th>Bahan</th><th>Porsi</th><th>Harga Satuan</th><th>Subtotal</th></tr>
                </thead>
                <tbody>
                <?php
                $stmt = $db->prepare("
                    SELECT b.nama, b.harga, rb.porsi, b.id as bahan_id
                    FROM racikan_bahan rb 
                    JOIN bahan b ON rb.bahan_id = b.id 
                    WHERE rb.racikan_id = ?
                ");
                $stmt->execute([$r['id']]);
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $total = 0;
                foreach ($items as $item):
                    $subtotal = $item['harga'] * $item['porsi'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nama']) ?></td>
                        <td><?= $item['porsi'] ?></td>
                        <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <p class="total">Total: Rp <?= number_format($total, 0, ',', '.') ?></p>

            <!-- Tombol aksi -->
            <form method="POST" action="load_racikan.php" style="display:inline">
                <input type="hidden" name="racikan_id" value="<?= $r['id'] ?>">
                <button type="submit" class="btn btn-load">🛒 Load ke Keranjang</button>
            </form>
            <a href="edit_racikan.php?id=<?= $r['id'] ?>" class="btn btn-edit">✏️ Edit</a>
            <form method="POST" action="hapus_racikan.php" style="display:inline" onsubmit="return confirm('Hapus racikan ini?')">
                <input type="hidden" name="racikan_id" value="<?= $r['id'] ?>">
                <button type="submit" class="btn btn-hapus">🗑️ Hapus</button>
            </form>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>