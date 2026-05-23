<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['racikan_id'])) {
    $racikan_id = (int)$_POST['racikan_id'];

    $stmt = $db->prepare("
        SELECT b.id, b.nama, b.harga, b.jenis, rb.porsi
        FROM racikan_bahan rb
        JOIN bahan b ON rb.bahan_id = b.id
        WHERE rb.racikan_id = ?
    ");
    $stmt->execute([$racikan_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $_SESSION['keranjang'] = [];
    foreach ($items as $item) {
        $_SESSION['keranjang'][] = [
            'id'    => $item['id'],
            'nama'  => $item['nama'],
            'harga' => $item['harga'],
            'jenis' => $item['jenis'],
            'porsi' => $item['porsi'],
        ];
    }
}

header('Location: keranjang.php');
exit;