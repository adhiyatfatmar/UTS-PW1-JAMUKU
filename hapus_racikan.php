<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['racikan_id'])) {
    $id = (int)$_POST['racikan_id'];

    $db->prepare("DELETE FROM racikan_bahan WHERE racikan_id = ?")->execute([$id]);
    $db->prepare("DELETE FROM racikan WHERE id = ?")->execute([$id]);
}

header('Location: daftar_racikan.php?msg=Racikan berhasil dihapus');
exit;