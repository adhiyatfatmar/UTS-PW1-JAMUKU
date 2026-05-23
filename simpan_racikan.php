<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_racikan']);
    
    if (!empty($nama) && !empty($_SESSION['keranjang'])) {
        $stmt = $db->prepare("INSERT INTO racikan (nama_racikan) VALUES (?)");
        $stmt->execute([$nama]);
        $racikan_id = $db->lastInsertId();

        foreach ($_SESSION['keranjang'] as $item) {
            $stmt2 = $db->prepare("INSERT INTO racikan_bahan (racikan_id, bahan_id, porsi) VALUES (?, ?, ?)");
            $stmt2->execute([$racikan_id, $item['id'], $item['porsi']]);
        }

        header('Location: daftar_racikan.php?msg=Racikan berhasil disimpan!');
        exit;
    }
}

header('Location: racikan.php');
exit;