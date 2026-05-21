<?php
session_start();

$total = 0;
foreach ($_SESSION['keranjang'] ?? [] as $item) {
    $total += $item['harga'] * $item['porsi'];
}

$keranjang = $_SESSION['keranjang'] ?? [];

$_SESSION['keranjang'] = [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jamuku - Pembayaran Berhasil</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 60px auto; text-align: center; background: #fffbf0; }
        .card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #28a745; }
        .total { font-size: 28px; font-weight: bold; color: #c8860a; margin: 20px 0; }
        a { background: #c8860a; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>✅ Pembayaran Berhasil!</h1>
        <p>Terima kasih telah memesan jamu di Jamuku.</p>
        <div class="total">Total: Rp <?= number_format($total, 0, ',', '.') ?></div>
        <p>Racikan jamu Anda sedang diproses.</p>
        <a href="index.php">🌿 Racik Lagi</a>
    </div>
</body>
</html>