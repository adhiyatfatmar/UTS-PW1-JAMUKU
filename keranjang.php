<?php
session_start();
require 'db.php';


if (isset($_GET['hapus'])) {
    $hapus_id = (int)$_GET['hapus'];
    foreach ($_SESSION['keranjang'] as $key => $item) {
        if ($item['id'] == $hapus_id) {
            unset($_SESSION['keranjang'][$key]);
            break;
        }
    }
    $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
    header('Location: keranjang.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    foreach ($_SESSION['keranjang'] as &$item) {
        $id = $item['id'];
        if (isset($_POST['porsi'][$id])) {
            $p = (int)$_POST['porsi'][$id];
            $item['porsi'] = max(1, $p);
        }
    }
    header('Location: keranjang.php');
    exit;
}


$total = 0;
foreach ($_SESSION['keranjang'] ?? [] as $item) {
    $total += $item['harga'] * $item['porsi'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jamuku - Keranjang</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; background: #fffbf0; }
        h1 { color: #7a4f01; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { padding: 10px 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #c8860a; color: white; }
        .total-row td { font-weight: bold; font-size: 18px; background: #fff3cd; }
        .hapus { color: red; text-decoration: none; font-weight: bold; }
        .hapus:hover { color: darkred; }
        .btn-update { background: #c8860a; color: white; padding: 8px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-bayar { background: #28a745; color: white; padding: 12px 30px; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; margin-top: 16px; }
        .nav a { background: #555; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; }
        input[type=number] { width: 55px; padding: 4px; }
        .kosong { text-align: center; padding: 40px; color: #888; }
    </style>
</head>
<body>
    <h1>🛒 Keranjang Belanja</h1>
    <div style="margin-bottom:16px">
        <a class="nav a" href="index.php" style="background:#555;color:white;padding:8px 16px;text-decoration:none;border-radius:4px;">← Tambah Bahan Lagi</a>
    </div>

    <?php if (empty($_SESSION['keranjang'])): ?>
        <p class="kosong">Keranjang kosong. <a href="index.php">Pilih bahan dulu</a></p>
    <?php else: ?>
    <form method="POST" action="keranjang.php">
        <table>
            <thead>
                <tr>
                    <th>Bahan</th>
                    <th>Jenis</th>
                    <th>Harga Satuan</th>
                    <th>Porsi</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['keranjang'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nama']) ?></td>
                    <td><?= htmlspecialchars($item['jenis']) ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td>
                        <input type="number" name="porsi[<?= $item['id'] ?>]" value="<?= $item['porsi'] ?>" min="1" max="99">
                    </td>
                    <td>Rp <?= number_format($item['harga'] * $item['porsi'], 0, ',', '.') ?></td>
                    <td><a class="hapus" href="keranjang.php?hapus=<?= $item['id'] ?>" onclick="return confirm('Hapus <?= htmlspecialchars($item['nama']) ?>?')">Hapus</a></td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="4" style="text-align:right">Total:</td>
                    <td colspan="2">Rp <?= number_format($total, 0, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>
        <button type="submit" name="update" class="btn-update">Update Porsi</button>
    </form>

    <form method="POST" action="bayar.php">
        <button type="submit" class="btn-bayar">💳 Bayar Sekarang</button>
    </form>
    <?php endif; ?>
    <a href="simpan_racikan.php" style="display:inline-block;margin-top:10px;background:#7a4f01;color:white;padding:12px 24px;border-radius:6px;text-decoration:none;">💾 Simpan Racikan</a>
<a href="daftar_racikan.php" style="display:inline-block;margin-top:10px;background:#555;color:white;padding:12px 24px;border-radius:6px;text-decoration:none;">📋 Lihat Racikan</a>   
</body>
</html>