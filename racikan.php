<?php
session_start();
require 'db.php';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jamuku - Simpan Racikan</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 20px; background: #fffbf0; }
        h1 { color: #7a4f01; }
        input[type=text] { width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ddd; border-radius: 6px; margin: 10px 0; }
        button { background: #c8860a; color: white; padding: 12px 30px; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; }
        a { color: #c8860a; }
    </style>
</head>
<body>
    <h1>💾 Simpan Racikan</h1>
    <?php if (empty($_SESSION['keranjang'])): ?>
        <p>Keranjang kosong. <a href="index.php">Pilih bahan dulu</a></p>
    <?php else: ?>
        <p>Beri nama racikan jamu mu:</p>
        <form method="POST" action="simpan_racikan.php">
            <input type="text" name="nama_racikan" placeholder="Contoh: Jamu sehat seperti mbg" required>
            <button type="submit">Simpan Racikan</button>
        </form>
        <br>
        <a href="keranjang.php">← Kembali ke Keranjang</a>
    <?php endif; ?>
</body>
</html>