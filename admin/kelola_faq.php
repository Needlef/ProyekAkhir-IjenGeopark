<?php
session_start();
require '../includes/koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: login.php");
    exit;
}

// ==========================================
// ALUR LOGIKA CREATE (Menambah Data FAQ)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_faq'])) {
    $pertanyaan = $_POST['pertanyaan'];
    $jawaban    = $_POST['jawaban'];
    
    $sql = "INSERT INTO faq (pertanyaan, jawaban) VALUES (:pertanyaan, :jawaban)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'pertanyaan' => $pertanyaan,
        'jawaban'    => $jawaban
    ]);
    
    header("Location: kelola_faq.php");
    exit;
}

// ==========================================
// ALUR LOGIKA READ (Menarik Data FAQ)
// ==========================================
$daftar_faq = [];
$stmt = $pdo->query("SELECT * FROM faq ORDER BY id DESC");
while ($baris = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $daftar_faq[] = $baris;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola FAQ - Admin</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 5px; max-width: 900px; margin: auto; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input[type="text"], .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ccc; box-sizing: border-box; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #222; color: white; }
        .btn { padding: 8px 15px; background: #28a745; color: white; border: none; cursor: pointer; text-decoration: none; font-size: 14px; border-radius: 4px; }
        .btn:hover { background: #218838; }
        .btn-back { background: #555; display: inline-block; margin-bottom: 20px; }
        .btn-delete { background: #dc3545; }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="btn btn-back">⬅ Kembali ke Dashboard</a>
    <h2>Tambah FAQ Baru</h2>

    <form method="POST" action="">
        <div class="form-group">
            <label>Pertanyaan</label>
            <input type="text" name="pertanyaan" required>
        </div>
        <div class="form-group">
            <label>Jawaban</label>
            <textarea name="jawaban" rows="4" required></textarea>
        </div>
        <button type="submit" name="submit_faq" class="btn">Simpan FAQ</button>
    </form>

    <hr style="margin: 40px 0; border: 0; border-top: 1px solid #eee;">

    <h2>Daftar FAQ</h2>
    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="35%">Pertanyaan</th>
                <th width="45%">Jawaban</th>
                <th width="15%">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($daftar_faq as $faq): ?>
            <tr>
                <td><?= $faq['id'] ?></td>
                <td><?= htmlspecialchars($faq['pertanyaan']) ?></td>
                <td><?= nl2br(htmlspecialchars($faq['jawaban'])) ?></td>
                <td>
                    <!-- Link menuju proses hapus dengan mengirim ID -->
                    <a href="hapus_faq.php?id=<?= $faq['id'] ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus FAQ ini?');">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>