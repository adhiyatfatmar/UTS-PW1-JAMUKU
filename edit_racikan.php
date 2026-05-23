<?php
session_start();
require 'db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: daftar_racikan.php'); exit; }


$r = $db->prepare("SELECT * FROM racikan WHERE id = ?");
$r->execute([$id]);
$racikan = $r->fetch(PDO::FETCH_ASSOC);
if (!$racikan) { header('Location: daftar_racikan.php'); exit; }


$stmtRb = $db->prepare("SELECT bahan_id, porsi FROM racikan_bahan WHERE racikan_id = ?");
$stmtRb->execute([$id]);
$bahanDipilih = [];
foreach ($stmtRb->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $bahanDipilih[$row['bahan_id']] = $row['porsi'];
}


$semuaBahan = $db->query("SELECT * FROM bahan ORDER BY jenis, nama")->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaBaru = trim($_POST['nama_racikan']);
    $bahanPost = $_POST['bahan'] ?? []; // array [bahan_id => porsi]

    if (!empty($namaBaru) && !empty($bahanPost)) {
        // Update nama
        $db->prepare("UPDATE racikan SET nama_racikan = ? WHERE id = ?")
           ->execute([$namaBaru, $id]);

        
        $db->prepare("DELETE FROM racikan_bahan WHERE racikan_id = ?")->execute([$id]);
        foreach ($bahanPost as $bahan_id => $porsi) {
            $porsi = max(1, (int)$porsi);
            $db->prepare("INSERT INTO racikan_bahan (racikan_id, bahan_id, porsi) VALUES (?, ?, ?)")
               ->execute([$id, (int)$bahan_id, $porsi]);
        }

        header('Location: daftar_racikan.php?msg=Racikan berhasil diupdate');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Racikan</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; background: #fffbf0; }
        h1 { color: #7a4f01; }
        input[type=text] { width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ddd; border-radius: 6px; margin: 8px 0 16px; box-sizing: border-box; }
        .bahan-list { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px; }
        .bahan-item { background: white; border: 1px solid #ddd; border-radius: 6px; padding: 10px; }
        .bahan-item label { font-weight: bold; display: block; margin-bottom: 4px; }
        .bahan-item input[type=number] { width: 70px; padding: 4px; border: 1px solid #ccc; border-radius: 4px; }
        .jenis-label { font-size: 12px; color: #888; }
        button[type=submit] { background: #c8860a; color: white; padding: 12px 30px; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; }
        a { color: #c8860a; }
        h2 { color: #7a4f01; font-size: 16px; margin-top: 20px; }
    </style>
    <script>
        
        function togglePorsi(cb, inputId) {
            const input = document.getElementById(inputId);
            input.disabled = !cb.checked;
            if (!cb.checked) input.value = '';
            else if (!input.value) input.value = 1;
        }
    </script>
</head>
<body>
    <h1>✏️ Edit Racikan</h1>
    <a href="daftar_racikan.php">← Kembali</a>

    <form method="POST">
        <label><strong>Nama Racikan:</strong></label>
        <input type="text" name="nama_racikan" value="<?= htmlspecialchars($racikan['nama_racikan']) ?>" required>

        <h2>Pilih Bahan:</h2>
        <div class="bahan-list">
        <?php
        $jenisSekarang = '';
        foreach ($semuaBahan as $b):
            $checked = isset($bahanDipilih[$b['id']]);
            $porsi   = $checked ? $bahanDipilih[$b['id']] : 1;
            $inputId = 'porsi_' . $b['id'];
        ?>
            <div class="bahan-item">
                <label>
                    <input type="checkbox" name="pilih_bahan[]" value="<?= $b['id'] ?>"
                        onchange="togglePorsi(this, '<?= $inputId ?>')"
                        <?= $checked ? 'checked' : '' ?>>
                    <?= htmlspecialchars($b['nama']) ?>
                </label>
                <span class="jenis-label"><?= $b['jenis'] ?> — Rp <?= number_format($b['harga'], 0, ',', '.') ?></span><br>
                <label style="font-weight:normal;font-size:13px">Porsi:
                    <input type="number" id="<?= $inputId ?>" name="bahan[<?= $b['id'] ?>]"
                        value="<?= $checked ? $porsi : '' ?>"
                        min="1" <?= $checked ? '' : 'disabled' ?>>
                </label>
            </div>
        <?php endforeach; ?>
        </div>

        <button type="submit">💾 Simpan Perubahan</button>
    </form>
</body>
</html>