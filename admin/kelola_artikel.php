<?php
// 1. Mulai Sesi dan Panggil Koneksi
session_start();
require '../includes/koneksi.php';

// 2. Proteksi Halaman (Hanya Admin yang boleh masuk)
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: login.php");
    exit;
}

// ==========================================
// ALUR LOGIKA CREATE (Menambah Data Artikel)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_artikel'])) {
    $judul     = $_POST['judul'];
    $label     = $_POST['label'];
    $ringkasan = $_POST['ringkasan'];
    $konten    = $_POST['konten'];
    
    // Inisialisasi variabel penyimpan rute/URL gambar akhir
    $gambar_final = ""; 

    // LOGIKA PENENTUAN GAMBAR (Fleksibel: Upload Fisik atau URL)
    if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === 0) {
        // Opsi 1: Admin mengunggah file fisik
        $nama_file = time() . "_" . $_FILES['gambar_file']['name']; 
        $tmp_file  = $_FILES['gambar_file']['tmp_name'];
        $rute_tujuan = "../uploads/" . $nama_file;
        
        if (move_uploaded_file($tmp_file, $rute_tujuan)) {
            $gambar_final = $nama_file;
        } else {
            $error_msg = "Gagal mengunggah gambar fisik!";
        }
        
    } elseif (!empty(trim($_POST['gambar_url']))) {
        // Opsi 2: Admin menggunakan URL gambar eksternal
        $gambar_final = trim($_POST['gambar_url']);
        
    } else {
        $error_msg = "Anda harus memilih salah satu: Upload gambar atau masukkan URL!";
    }

    // PROSES SIMPAN KE DATABASE
    if (!isset($error_msg) && $gambar_final !== "") {
        $sql = "INSERT INTO artikel (judul, label, ringkasan, konten, gambar) 
                VALUES (:judul, :label, :ringkasan, :konten, :gambar)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'judul'     => $judul,
            'label'     => $label,
            'ringkasan' => $ringkasan,
            'konten'    => $konten,
            'gambar'    => $gambar_final 
        ]);
        
        // Refresh halaman setelah sukses menyimpan
        header("Location: kelola_artikel.php");
        exit;
    }
}

// ==========================================
// ALUR LOGIKA READ (Menarik Data untuk Tabel)
// ==========================================
$daftar_artikel = [];
$stmt = $pdo->query("SELECT id, judul, label, gambar FROM artikel ORDER BY id DESC");

while ($baris = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $daftar_artikel[] = $baris;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Artikel - Admin</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 5px; max-width: 1000px; margin: auto; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input[type="text"], .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ccc; box-sizing: border-box; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 12px; text-align: left; }
        th { background-color: #222; color: white; }
        .btn { padding: 8px 15px; background: #28a745; color: white; border: none; cursor: pointer; text-decoration: none; font-size: 14px; border-radius: 4px; }
        .btn:hover { background: #218838; }
        .btn-back { background: #555; display: inline-block; margin-bottom: 20px; }
        .btn-back:hover { background: #333; }
        .btn-edit { background: #ffc107; color: black; }
        .btn-edit:hover { background: #e0a800; }
        .btn-delete { background: #dc3545; }
        .btn-delete:hover { background: #c82333; }
        img.thumb { width: 100px; height: auto; border-radius: 4px; object-fit: cover; }
        .opsi-gambar { background: #f9f9f9; padding: 15px; border: 1px dashed #ccc; border-radius: 4px; }
        .opsi-gambar p { font-size: 14px; margin: 5px 0; color: #555; }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="btn btn-back">⬅ Kembali ke Dashboard</a>
    <h2>Tambah Artikel Baru</h2>

    <?php if (isset($error_msg)): ?>
        <p style="color:red; font-weight:bold;"><?= $error_msg ?></p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label>Judul Destinasi</label>
            <input type="text" name="judul" required>
        </div>
        <div class="form-group">
            <label>Label (Contoh: Kawah / Fenomena)</label>
            <input type="text" name="label">
        </div>
        <div class="form-group">
            <label>Ringkasan (Untuk Card di Beranda)</label>
            <textarea name="ringkasan" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label>Konten Lengkap (Paragraf Artikel)</label>
            <textarea name="konten" rows="8" required></textarea>
        </div>
        
        <div class="form-group opsi-gambar">
            <label>Opsi Gambar Utama (Pilih Salah Satu)</label>
            <p>Opsi 1: Masukkan URL Gambar (Backward Compatible)</p>
            <input type="text" name="gambar_url" placeholder="Contoh: https://asset.kompas.com/gambar.jpg">
            
            <p style="margin-top: 15px;">Opsi 2: Atau Upload File Fisik</p>
            <input type="file" name="gambar_file" accept="image/*">
        </div>
        
        <button type="submit" name="submit_artikel" class="btn">Simpan Artikel</button>
    </form>

    <hr style="margin: 40px 0; border: 0; border-top: 1px solid #eee;">

    <h2>Daftar Artikel</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Label</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($daftar_artikel as $artikel): ?>
            <tr>
                <td><?= $artikel['id'] ?></td>
                <td>
                    <?php
                        // Cek apakah data gambar mengandung 'http'
                        if (strpos($artikel['gambar'], 'http') === 0) {
                            $sumber_gambar = htmlspecialchars($artikel['gambar']);
                        } else {
                            $sumber_gambar = "../uploads/" . htmlspecialchars($artikel['gambar']);
                        }
                    ?>
                    <img src="<?= $sumber_gambar ?>" class="thumb" alt="Thumbnail">
                </td>
                <td><?= htmlspecialchars($artikel['judul']) ?></td>
                <td><?= htmlspecialchars($artikel['label']) ?></td>
                <td>
                    <!-- Tombol Edit dan Hapus disiapkan untuk tahap selanjutnya -->
                    <a href="#" class="btn btn-edit">Edit</a>
                    <a href="#" class="btn btn-delete">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
            
            <?php if (empty($daftar_artikel)): ?>
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px;">Belum ada artikel yang ditambahkan.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>