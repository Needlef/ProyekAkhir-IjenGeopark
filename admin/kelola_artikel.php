<?php
// 1. Mulai Sesi dan Panggil Koneksi
session_start();
require '../includes/koneksi.php';

// 2. Proteksi Halaman
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: login.php");
    exit;
}

// ==========================================
// ALUR LOGIKA CREATE (UPLOAD/URL + CSS CROP)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_artikel'])) {
    $judul     = $_POST['judul'];
    $label     = $_POST['label'];
    $ringkasan = $_POST['ringkasan'];
    $konten    = $_POST['konten'];
    
    // Inisialisasi gambar akhir
    $gambar_final = ""; 

    // 1. CEK OPSI GAMBAR (Upload Fisik vs URL)
    if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === 0) {
        $nama_file = time() . "_" . $_FILES['gambar_file']['name']; 
        $tmp_file  = $_FILES['gambar_file']['tmp_name'];
        $rute_tujuan = "../uploads/" . $nama_file;
        
        if (move_uploaded_file($tmp_file, $rute_tujuan)) {
            $gambar_final = $nama_file;
        } else {
            $error_msg = "Gagal mengunggah gambar fisik!";
        }
    } elseif (!empty(trim($_POST['gambar_url']))) {
        $gambar_final = trim($_POST['gambar_url']);
    } else {
        $error_msg = "Pilih salah satu: Upload gambar atau masukkan URL!";
    }

    // 2. TANGKAP DATA KOORDINAT CSS
    $css_width  = $_POST['css_width'] ?? 100;
    $css_height = $_POST['css_height'] ?? 100;
    $css_left   = $_POST['css_left'] ?? 0;
    $css_top    = $_POST['css_top'] ?? 0;

    // 3. PROSES SIMPAN KE DATABASE
    if (!isset($error_msg) && $gambar_final !== "") {
        $sql = "INSERT INTO artikel (judul, label, ringkasan, konten, gambar, css_width, css_height, css_left, css_top) 
                VALUES (:judul, :label, :ringkasan, :konten, :gambar, :cw, :ch, :cl, :ct)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'judul'     => $judul,
            'label'     => $label,
            'ringkasan' => $ringkasan,
            'konten'    => $konten,
            'gambar'    => $gambar_final,
            'cw' => $css_width, 'ch' => $css_height, 'cl' => $css_left, 'ct' => $css_top
        ]);
        
        header("Location: kelola_artikel.php");
        exit;
    }
}

// ==========================================
// ALUR LOGIKA READ
// ==========================================
$daftar_artikel = [];
$stmt = $pdo->query("SELECT id, judul, label, gambar, css_width, css_height, css_left, css_top FROM artikel ORDER BY id DESC");

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
        .btn-edit { background: #ffc107; color: black; }
        .btn-delete { background: #dc3545; }
        .opsi-gambar { background: #f9f9f9; padding: 15px; border: 1px dashed #ccc; border-radius: 4px; }
        
        /* Tampilan mini crop di tabel */
        .thumb-container { width: 120px; aspect-ratio: 16/9; overflow: hidden; position: relative; border-radius: 4px; }
    </style>
    <!-- Library Cropper.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
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
            <label>Label</label>
            <input type="text" name="label">
        </div>
        <div class="form-group">
            <label>Ringkasan</label>
            <textarea name="ringkasan" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label>Konten Lengkap</label>
            <textarea name="konten" rows="8" required></textarea>
        </div>
        
        <div class="form-group opsi-gambar">
            <label>Opsi Gambar (Pilih Salah Satu)</label>
            
            <p style="margin-top: 10px; font-weight: bold;">Opsi 1: Upload File Fisik</p>
            <input type="file" name="gambar_file" id="gambar_file" accept="image/*">
            
            <p style="margin-top: 15px; font-weight: bold;">Opsi 2: URL Gambar Cloud</p>
            <div style="display: flex; gap: 10px;">
                <input type="text" name="gambar_url" id="gambar_url" placeholder="Paste link di sini...">
                <button type="button" id="btnPreviewUrl" class="btn" style="background: #17a2b8; white-space: nowrap;">Load URL</button>
            </div>
            
            <!-- Tempat Preview Cropper -->
            <div id="cropArea" style="margin-top: 15px; max-height: 400px; display: none;">
                <p style="font-size: 12px; color: #666; margin-bottom: 5px;">Geser kotak di bawah untuk menentukan posisi fokus (Crop)</p>
                <img id="imageToCrop" style="max-width: 100%;">
            </div>
            
            <!-- Input tersembunyi kalkulasi CSS -->
            <input type="hidden" name="css_width" id="css_width" value="100">
            <input type="hidden" name="css_height" id="css_height" value="100">
            <input type="hidden" name="css_left" id="css_left" value="0">
            <input type="hidden" name="css_top" id="css_top" value="0">
        </div>
        
        <button type="submit" name="submit_artikel" class="btn">Simpan Artikel</button>
    </form>

    <hr style="margin: 40px 0; border: 0; border-top: 1px solid #eee;">

    <h2>Daftar Artikel</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Preview Crop</th>
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
                        // 1. Logika deteksi URL vs File Fisik
                        if (strpos($artikel['gambar'], 'http') === 0) {
                            $sumber_gambar = htmlspecialchars($artikel['gambar']);
                        } else {
                            $sumber_gambar = "../uploads/" . htmlspecialchars($artikel['gambar']);
                        }
                        
                        // 2. LOGIKA BACKWARD COMPATIBLE
                        $punya_koordinat = !empty($artikel['css_width']) && $artikel['css_width'] > 0;
                        
                        $c_w = $punya_koordinat ? $artikel['css_width'] : 100;
                        $c_h = $punya_koordinat ? $artikel['css_height'] : 100;
                        $c_l = $punya_koordinat ? $artikel['css_left'] : 0;
                        $c_t = $punya_koordinat ? $artikel['css_top'] : 0;
                        $obj_fit = $punya_koordinat ? 'fill' : 'cover';
                    ?>
                    <!-- Thumbnail dengan implementasi Fallback -->
                    <div class="thumb-container">
                        <img src="<?= $sumber_gambar ?>" style="
                            position: absolute;
                            width: <?= $c_w ?>%;
                            height: <?= $c_h ?>%;
                            left: <?= $c_l ?>%;
                            top: <?= $c_t ?>%;
                            max-width: none;
                            object-fit: <?= $obj_fit ?>;
                        ">
                    </div>
                </td>
                <td><?= htmlspecialchars($artikel['judul']) ?></td>
                <td><?= htmlspecialchars($artikel['label']) ?></td>
                <td>
                    <a href="edit_artikel.php?id=<?= $artikel['id'] ?>" class="btn btn-edit">Edit</a>
                    <a href="hapus_artikel.php?id=<?= $artikel['id'] ?>" class="btn btn-delete" onclick="return confirm('Yakin hapus?');">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    let cropper;
    const cropArea = document.getElementById('cropArea');
    const img = document.getElementById('imageToCrop');

    // Fungsi Utama Inisialisasi Cropper
    function initCropper(imageSrc) {
        img.src = imageSrc;
        cropArea.style.display = 'block';
        
        if(cropper) cropper.destroy();
        
        img.onload = function() {
            cropper = new Cropper(img, {
                aspectRatio: 16 / 9,
                viewMode: 1,
                crop: function(event) {
                    const data = event.detail; 
                    const natW = img.naturalWidth;
                    const natH = img.naturalHeight;
                    
                    document.getElementById('css_width').value = (natW / data.width) * 100;
                    document.getElementById('css_height').value = (natH / data.height) * 100;
                    document.getElementById('css_left').value = -(data.x / data.width) * 100;
                    document.getElementById('css_top').value = -(data.y / data.height) * 100;
                }
            });
        };
    }

    // TRIGGER 1: Saat Admin Pilih File Fisik
    document.getElementById('gambar_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Bersihkan input URL agar PHP tidak bingung
            document.getElementById('gambar_url').value = ""; 
            
            const reader = new FileReader();
            reader.onload = function(event) {
                initCropper(event.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // TRIGGER 2: Saat Admin Tekan Tombol Load URL
    document.getElementById('btnPreviewUrl').addEventListener('click', function() {
        const url = document.getElementById('gambar_url').value;
        if(url) {
            // Bersihkan input file agar PHP tidak bingung
            document.getElementById('gambar_file').value = ""; 
            
            initCropper(url);
        }
    });
</script>

</body>
</html>