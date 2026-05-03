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

// Ambil data lama dari database, termasuk koordinat CSS
$stmt = $pdo->prepare("SELECT * FROM artikel WHERE id = :id");
$stmt->execute(['id' => $id_artikel]);
$artikel_lama = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$artikel_lama) {
    die("Artikel tidak ditemukan!");
}

// ==========================================
// ALUR LOGIKA UPDATE (VERSI KOORDINAT CSS)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_artikel'])) {
    $judul     = $_POST['judul'];
    $label     = $_POST['label'];
    $ringkasan = $_POST['ringkasan'];
    $konten    = $_POST['konten'];
    
    // Tangkap data URL dan koordinat dari form
    $gambar_url = trim($_POST['gambar_url']);
    $css_width  = $_POST['css_width'];
    $css_height = $_POST['css_height'];
    $css_left   = $_POST['css_left'];
    $css_top    = $_POST['css_top'];

    if (!empty($gambar_url)) {
        // Update query termasuk data CSS
        $sql = "UPDATE artikel SET 
                judul = :judul, 
                label = :label, 
                ringkasan = :ringkasan, 
                konten = :konten, 
                gambar = :gambar,
                css_width = :cw,
                css_height = :ch,
                css_left = :cl,
                css_top = :ct
                WHERE id = :id";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'judul'     => $judul,
            'label'     => $label,
            'ringkasan' => $ringkasan,
            'konten'    => $konten,
            'gambar'    => $gambar_url,
            'cw'        => $css_width,
            'ch'        => $css_height,
            'cl'        => $css_left,
            'ct'        => $css_top,
            'id'        => $id_artikel
        ]);
        
        header("Location: kelola_artikel.php");
        exit;
    } else {
        $error_msg = "URL Gambar wajib diisi!";
    }
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
    </style>
    <!-- Tambahkan Library Cropper.js -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
</head>
<body>

<div class="container">
    <a href="kelola_artikel.php" class="btn btn-back">⬅ Batal & Kembali</a>
    <h2>Edit Artikel</h2>

    <?php if (isset($error_msg)): ?>
        <p style="color:red; font-weight:bold;"><?= $error_msg ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Judul Destinasi</label>
            <input type="text" name="judul" value="<?= htmlspecialchars($artikel_lama['judul']) ?>" required>
        </div>
        <div class="form-group">
            <label>Label</label>
            <input type="text" name="label" value="<?= htmlspecialchars($artikel_lama['label']) ?>">
        </div>
        <div class="form-group">
            <label>Ringkasan</label>
            <textarea name="ringkasan" rows="3" required><?= htmlspecialchars($artikel_lama['ringkasan']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Konten Lengkap</label>
            <textarea name="konten" rows="8" required><?= htmlspecialchars($artikel_lama['konten']) ?></textarea>
        </div>
        
        <div class="form-group opsi-gambar">
            <label>Gambar & Posisi Crop Saat Ini</label>
            
            <?php
                // 1. Deteksi URL vs Fisik untuk Preview Lama
                if (strpos($artikel_lama['gambar'], 'http') === 0) {
                    $sumber_gambar_lama = htmlspecialchars($artikel_lama['gambar']);
                } else {
                    $sumber_gambar_lama = "../uploads/" . htmlspecialchars($artikel_lama['gambar']);
                }

                // 2. LOGIKA BACKWARD COMPATIBLE
                $punya_koordinat_lama = !empty($artikel_lama['css_width']) && $artikel_lama['css_width'] > 0;
                
                $e_w = $punya_koordinat_lama ? $artikel_lama['css_width'] : 100;
                $e_h = $punya_koordinat_lama ? $artikel_lama['css_height'] : 100;
                $e_l = $punya_koordinat_lama ? $artikel_lama['css_left'] : 0;
                $e_t = $punya_koordinat_lama ? $artikel_lama['css_top'] : 0;
                $e_fit = $punya_koordinat_lama ? 'fill' : 'cover';
            ?>
            
            <!-- Tampilan Preview Crop Lama -->
            <div style="width: 250px; aspect-ratio: 16/9; overflow: hidden; position: relative; margin-bottom: 15px; border: 2px solid #ccc; border-radius: 4px;">
                <img src="<?= $sumber_gambar_lama ?>" style="
                    position: absolute;
                    width: <?= $e_w ?>%;
                    height: <?= $e_h ?>%;
                    left: <?= $e_l ?>%;
                    top: <?= $e_t ?>%;
                    max-width: none;
                    object-fit: <?= $e_fit ?>;
                ">
            </div>
            
            <!-- ... (Sisa kode input URL dan Hidden JS di bawahnya biarkan sama) ... -->
            
            <p style="font-size: 13px; color: #555; margin-bottom: 15px;"><i>Biarkan nilai di bawah jika tidak ingin mengganti gambar atau mengubah posisi crop.</i></p>

            <label>URL Gambar Cloud</label>
            <input type="text" name="gambar_url" id="gambar_url" value="<?= htmlspecialchars($artikel_lama['gambar']) ?>" required>
            <button type="button" id="btnPreview" class="btn" style="background: #17a2b8; color: white; margin-top: 10px;">Load & Atur Posisi (Crop)</button>
            
            <!-- Tempat Preview Cropper -->
            <div id="cropArea" style="margin-top: 15px; max-height: 400px; display: none;">
                <img id="imageToCrop" style="max-width: 100%;">
            </div>
            
            <!-- Input tersembunyi dengan default value dari database -->
            <input type="hidden" name="css_width" id="css_width" value="<?= $artikel_lama['css_width'] ?>">
            <input type="hidden" name="css_height" id="css_height" value="<?= $artikel_lama['css_height'] ?>">
            <input type="hidden" name="css_left" id="css_left" value="<?= $artikel_lama['css_left'] ?>">
            <input type="hidden" name="css_top" id="css_top" value="<?= $artikel_lama['css_top'] ?>">
        </div>
        
        <button type="submit" name="update_artikel" class="btn">Simpan Perubahan</button>
    </form>
</div>

<script>
    let cropper;
    document.getElementById('btnPreview').addEventListener('click', function() {
        const url = document.getElementById('gambar_url').value;
        const img = document.getElementById('imageToCrop');
        const cropArea = document.getElementById('cropArea');
        
        if(url) {
            img.src = url;
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
                        
                        const cssW = (natW / data.width) * 100;
                        const cssH = (natH / data.height) * 100;
                        const cssL = -(data.x / data.width) * 100;
                        const cssT = -(data.y / data.height) * 100;
                        
                        document.getElementById('css_width').value = cssW;
                        document.getElementById('css_height').value = cssH;
                        document.getElementById('css_left').value = cssL;
                        document.getElementById('css_top').value = cssT;
                    }
                });
            };
        }
    });
</script>

</body>
</html>