<?php
session_start();
require 'db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $porsi = (int)$_POST['porsi'];
    if ($porsi < 1) $porsi = 1;

    $bahan_dipilih = $_POST['bahan'] ?? [];

    if (!empty($bahan_dipilih)) {
        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        foreach ($bahan_dipilih as $id) {
            $stmt = $db->prepare("SELECT * FROM bahan WHERE id = ?");
            $stmt->execute([$id]);
            $bahan = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($bahan) {
                
                $found = false;
                foreach ($_SESSION['keranjang'] as &$item) {
                    if ($item['id'] == $id) {
                        $item['porsi'] += $porsi;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $_SESSION['keranjang'][] = [
                        'id'    => $bahan['id'],
                        'nama'  => $bahan['nama'],
                        'harga' => $bahan['harga'],
                        'jenis' => $bahan['jenis'],
                        'porsi' => $porsi
                    ];
                }
            }
        }
    }
    header('Location: keranjang.php');
    exit;
}


$stmt = $db->query("SELECT * FROM bahan ORDER BY jenis, nama");
$semua_bahan = $stmt->fetchAll(PDO::FETCH_ASSOC);

$per_jenis = [];
foreach ($semua_bahan as $b) {
    $per_jenis[$b['jenis']][] = $b;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jamuku - Racik Jamu</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 0 auto; padding: 20px; background: #fffbf0; }
        h1 { color: #7a4f01; }
        .kategori { margin-bottom: 20px; }
        .kategori h2 { background: #c8860a; color: white; padding: 8px 12px; border-radius: 4px; }
        .bahan-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        .bahan-card { border: 1px solid #ddd; padding: 10px; border-radius: 6px; background: white; }
        .bahan-card label { font-weight: bold; cursor: pointer; }
        .bahan-card small { color: #666; display: block; margin-top: 4px; }
        .bahan-card .harga { color: #c8860a; font-weight: bold; }
        .porsi-section { margin: 20px 0; }
        .porsi-section input { width: 60px; padding: 6px; font-size: 16px; }
        button[type=submit] { background: #c8860a; color: white; padding: 12px 30px; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; }
        button[type=submit]:hover { background: #7a4f01; }
        .nav { margin-bottom: 20px; }
        .nav a { background: #555; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>🌿 Jamuku — Racik Jamu Sendiri</h1>
    <div class="nav">
        <a href="keranjang.php">🛒 Lihat Keranjang (<?= count($_SESSION['keranjang'] ?? []) ?> item)</a>
    </div>

    <form method="POST" action="index.php">
        <?php foreach ($per_jenis as $jenis => $bahans): ?>
        <div class="kategori">
            <h2><?= htmlspecialchars($jenis) ?></h2>
            <div class="bahan-grid">
                <?php foreach ($bahans as $b): ?>
                <div class="bahan-card">
                    <label>
                        <input type="checkbox" name="bahan[]" value="<?= $b['id'] ?>">
                        <?= htmlspecialchars($b['nama']) ?>
                    </label>
                    <span class="harga">Rp <?= number_format($b['harga'], 0, ',', '.') ?></span>
                    <small><?= htmlspecialchars($b['deskripsi']) ?></small>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="porsi-section">
            <label><strong>Jumlah Porsi:</strong></label>
            <input type="number" name="porsi" value="1" min="1" max="99">
        </div>

        <button type="submit">Tambah ke Keranjang 🛒</button>
    </form>
</body>
</html>