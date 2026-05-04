<?php
// 1. Panggil Koneksi Database
require 'includes/koneksi.php';

// 2. Tangkap ID dari URL (Contoh: artikel.php?id=1)
// Cek apakah parameter 'id' ada dan apakah itu sebuah angka
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_artikel = $_GET['id'];

    // 3. Tarik data spesifik dari tabel 'artikel' berdasarkan ID
    $stmt = $pdo->prepare("SELECT * FROM artikel WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id_artikel]);
    
    // Ambil datanya ke dalam bentuk array asosiatif
    $artikel = $stmt->fetch(PDO::FETCH_ASSOC);

    // 4. Jika ID tidak ditemukan di database (misal pengunjung iseng mengetik id=999)
    if (!$artikel) {
        die("<h2 style='text-align:center; margin-top:50px;'>Artikel tidak ditemukan. <a href='index.php'>Kembali ke Beranda</a></h2>");
    }

} else {
    // Jika diakses tanpa ID (hanya mengetik artikel.php)
    header("Location: index.php");
    exit;
}

// 5. Logika gambar (Kompatibel dengan URL eksternal atau Upload Fisik)
// Logika gambar (URL vs Fisik)
    if (strpos($artikel['gambar'], 'http') === 0) {
        $sumber_gambar = htmlspecialchars($artikel['gambar']);
    } else {
        $sumber_gambar = "uploads/" . htmlspecialchars($artikel['gambar']);
    }

    // LOGIKA BACKWARD COMPATIBLE
    $punya_koordinat = !empty($artikel['css_width']) && $artikel['css_width'] > 0;
    
    $c_w = $punya_koordinat ? $artikel['css_width'] : 100;
    $c_h = $punya_koordinat ? $artikel['css_height'] : 100;
    $c_l = $punya_koordinat ? $artikel['css_left'] : 0;
    $c_t = $punya_koordinat ? $artikel['css_top'] : 0;
    $obj_fit = $punya_koordinat ? 'fill' : 'cover';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Judul tab browser otomatis berubah sesuai judul artikel -->
    <title><?= htmlspecialchars($artikel['judul']) ?> - Ijen Geopark</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <nav>
            <div class="logo"><a href="index.php">Ijen Geopark</a></div>
            <a href="#" class="contact-link">Contact</a>
        </nav>
    </header>

    <!-- BAGIAN HERO GAMBAR UTAMA -->
    <section class="hero-article" style="width: 100%; height: 400px; overflow: hidden; position: relative;">
            <img src="<?= $sumber_gambar ?>" alt="<?= htmlspecialchars($artikel['judul']) ?>" style="
                position: absolute;
                width: <?= $c_w ?>%;
                height: <?= $c_h ?>%;
                left: <?= $c_l ?>%;
                top: <?= $c_t ?>%;
                max-width: none;
                object-fit: <?= $obj_fit ?>;
            ">
        </section>
    <!-- BAGIAN KONTEN ARTIKEL -->
    <main class="article-content">
        <!-- Label / Eyebrow -->
        <p class="eyebrow"><?= htmlspecialchars($artikel['label']) ?></p>
        
        <!-- Judul Utama -->
        <h1 class="main-headline"><?= htmlspecialchars($artikel['judul']) ?></h1>
        
        <div class="article-text">
            <!-- nl2br digunakan agar enter di textarea form admin berubah menjadi tag <br> yang dibaca oleh HTML -->
            <p><?= nl2br(htmlspecialchars($artikel['konten'])) ?></p>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="footer-brand">
                <h2>Ijen Geopark</h2>
                <p>Find the majestic of Java</p>
                <div class="social-icons">
                    <a href="#"><img src="https://e7.pngegg.com/pngimages/624/712/png-clipart-instagram-logo-logo-computer-icons-insta-miscellaneous-sticker.png" alt="Instagram"></a>
                    <a href="#"><img src="https://png.pngtree.com/element_our/md/20180626/md_5b32227feb591.jpg" alt="LinkedIn"></a>
                    <a href="#"><img src="https://img.freepik.com/free-vector/twitter-new-2023-x-logo-white-background-vector_1017-45422.jpg?semt=ais_hybrid&w=740&q=80" alt="X"></a>
                </div>
            </div>
            
            <div class="footer-links-container">
                <div class="footer-column">
                    <h4>Features</h4>
                    <ul><li>Core features</li><li>Pro experience</li><li>Integrations</li></ul>
                </div>
                <div class="footer-column">
                    <h4>Learn more</h4>
                    <ul><li>Blog</li><li>Case studies</li><li>Customer stories</li><li>Best practices</li></ul>
                </div>
                <div class="footer-column">
                    <h4>Support</h4>
                    <ul><li>Contact</li><li>Support</li><li>Legal</li></ul>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>