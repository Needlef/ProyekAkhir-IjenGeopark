<?php
session_start();
require '../includes/koneksi.php';

// Proteksi Halaman
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: login.php");
    exit;
}

// 1. Tangkap ID dari URL untuk menarik data lama
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kelola_artikel.php");
    exit;
}
$id_artikel = $_GET['id'];

// Ambil data lama dari database
$stmt = $pdo->prepare("SELECT * FROM artikel WHERE id = :id");
$stmt->execute(['id' => $id_artikel]);
$artikel_lama = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$artikel_lama) {
    die("Artikel tidak ditemukan!");
}

// ==========================================
// ALUR LOGIKA UPDATE (Menyimpan Perubahan)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_artikel'])) {
    $judul     = $_POST['judul'];
    $label     = $_POST['label'];
    $ringkasan = $_POST['ringkasan'];
    $konten    = $_POST['konten'];
    
    // Secara default, gunakan gambar lama jika admin tidak mengganti gambar
    $gambar_final = $artikel_lama['gambar'];

    // Cek apakah admin mengunggah file gambar baru
    if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === 0) {
        $nama_file = time() . "_" . $_FILES['gambar_file']['name']; 
        $tmp_file  = $_FILES['gambar_file']['tmp_name'];
        $rute_tujuan = "../uploads/" . $nama_file;
        
        if (move_uploaded_file($tmp_file, $rute_tujuan)) {
            $gambar_final = $nama_file;
            
            // Hapus file gambar fisik yang lama agar server tidak penuh (opsional tapi disarankan)
            if (strpos($artikel_lama['gambar'], 'http') !== 0 && file_exists("../uploads/" . $artikel_lama['gambar'])) {
                unlink("../uploads/" . $artikel_lama['gambar']);
            }
        }
        
    // Cek apakah admin memasukkan URL gambar baru
    } elseif (!empty(trim($_POST['gambar_url']))) {
        $gambar_final = trim($_POST['gambar_url']);
    }

    // Eksekusi Update ke Database
    $sql = "UPDATE artikel SET 
            judul = :judul, 
            label = :label, 
            ringkasan = :ringkasan, 
            konten = :konten, 
            gambar = :gambar 
            WHERE id = :id";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'judul'     => $judul,
        'label'     => $label,
        'ringkasan' => $ringkasan,
        'konten'    => $konten,
        'gambar'    => $gambar_final,
        'id'        => $id_artikel
    ]);
    
    header("Location: kelola_artikel.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Artikel - Admin</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: white; padding: 20px; border-radius: 5px; max-width: 900px; margin: auto; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input[type="text"], .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ccc; box-sizing: border-box; border-radius: 4px; }
        .btn { padding: 8px 15px; background: #ffc107; color: black; border: none; cursor: pointer; text-decoration: none; font-size: 14px; border-radius: 4px; font-weight: bold; }
        .btn:hover { background: #e0a800; }
        .btn-back { background: #555; color: white; display: inline-block; margin-bottom: 20px; font-weight: normal; }
        .opsi-gambar { background: #e9ecef; padding: 15px; border: 1px solid #ccc; border-radius: 4px; }
        .gambar-lama { max-width: 200px; border-radius: 4px; margin-bottom: 10px; display: block; }
    </style>
</head>
<body>

<div class="container">
    <a href="kelola_artikel.php" class="btn btn-back">⬅ Batal & Kembali</a>
    <h2>Edit Artikel</h2>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label>Judul Destinasi</label>
            <!-- Data lama dimasukkan ke dalam atribut value -->
            <input type="text" name="judul" value="<?= htmlspecialchars($artikel_lama['judul']) ?>" required>
        </div>
        <div class="form-group">
            <label>Label</label>
            <input type="text" name="label" value="<?= htmlspecialchars($artikel_lama['label']) ?>">
        </div>
        <div class="form-group">
            <label>Ringkasan</label>
            <!-- Untuk textarea, data lama ditaruh di antara tag pembuka dan penutup -->
            <textarea name="ringkasan" rows="3" required><?= htmlspecialchars($artikel_lama['ringkasan']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Konten Lengkap</label>
            <textarea name="konten" rows="8" required><?= htmlspecialchars($artikel_lama['konten']) ?></textarea>
        </div>
        
        <div class="form-group opsi-gambar">
            <label>Gambar Saat Ini:</label>
            <?php
                if (strpos($artikel_lama['gambar'], 'http') === 0) {
                    $sumber_gambar = htmlspecialchars($artikel_lama['gambar']);
                } else {
                    $sumber_gambar = "../uploads/" . htmlspecialchars($artikel_lama['gambar']);
                }
            ?>
            <img src="<?= $sumber_gambar ?>" class="gambar-lama" alt="Gambar Lama">
            <p style="font-size: 12px; color: #555;"><i>Biarkan opsi di bawah kosong jika tidak ingin mengganti gambar.</i></p>
            
            <p style="margin-top: 15px; font-weight: bold;">Ganti dengan URL Gambar Baru:</p>
            <input type="text" name="gambar_url" placeholder="Contoh: https://asset.kompas.com/gambar-baru.jpg">
            
            <p style="margin-top: 15px; font-weight: bold;">Atau Ganti dengan Upload File Fisik:</p>
            <input type="file" name="gambar_file" accept="image/*">
        </div>
        
        <button type="submit" name="update_artikel" class="btn">Simpan Perubahan</button>
    </form>
</div>

</body>
</html>