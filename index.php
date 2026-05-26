<?php
// 1. Panggil Koneksi Database
require 'includes/koneksi.php';

// 2. Tarik Data Artikel dari Database (Diurutkan dari yang terbaru)
$stmt_artikel = $pdo->query("SELECT id, judul, ringkasan, gambar, css_width, css_height, css_left, css_top FROM artikel ORDER BY id DESC");
$artikel_data = $stmt_artikel->fetchAll(PDO::FETCH_ASSOC);

// 3. Tarik Data FAQ dari Database
$stmt_faq = $pdo->query("SELECT * FROM faq ORDER BY id ASC");
$faq_data = $stmt_faq->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ijen Geopark - Beranda</title>
    <!-- Pastikan style.css dan script.js berada di folder yang sama dengan file ini -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <nav>
            <div class="logo">Ijen Geopark</div>
            <div class="nav-right">
                <button id="theme-toggle" class="theme-btn" title="Toggle dark theme">🌙</button>
                <a href="#" class="contact-link">Contact</a>
            </div>
        </nav>
    </header>

    <section class="hero-index">
        <div class="hero-text">
            <h1>Ijen Geopark</h1>
            <p>Find the majestic of Java</p>
        </div>
    </section>

    <section class="ijen-geopark-intro">
        <div class="ijen-container">
            <h2 class="ijen-title">Welcome to Ijen UNESCO Global Geopark</h2>
            <p class="ijen-subtitle">Go ahead and say just a little more about what you do.</p>
            
            <p class="ijen-description">
                Spanning across the Banyuwangi and Bondowoso regencies, Ijen Geopark is a 
                world-class destination recognized by UNESCO. It is a living laboratory of the 
                Earth's evolution, offering a unique blend of mesmerizing volcanic landscapes, 
                lush highland ecosystems, and the enduring heritage of the local communities. 
                Whether you are an adventurer, a nature lover, or a culture enthusiast, Ijen 
                offers an unforgettable journey into the heart of Java.
            </p>
        </div>
    </section>

    <!-- BAGIAN KARTU ARTIKEL (DINAMIS DARI DATABASE) -->
    <section class="cards-section">
        <div class="cards-container">
            
            <?php if (count($artikel_data) > 0): ?>
                <?php foreach ($artikel_data as $row): ?>
                    <?php
                        // 1. Cek Sumber Gambar (URL vs Fisik)
                        if (strpos($row['gambar'], 'http') === 0) {
                            $bg_image = htmlspecialchars($row['gambar']);
                        } else {
                            $bg_image = "uploads/" . htmlspecialchars($row['gambar']);
                        }

                       
                        
                        $obj_fit ='cover';
                    ?>
                    
                        <div class="card">
                            <div style="width: 100%; aspect-ratio: 16/9; overflow: hidden; position: relative; border-radius: 12px 12px 0 0;">
                                <img src="<?= htmlspecialchars($bg_image) ?>" style="
                                    position: absolute;
                                    width: <?= $row['css_width'] ?: 100 ?>%;
                                    height: <?= $row['css_height'] ?: 100 ?>%;
                                    left: <?= $row['css_left'] ?: 0 ?>%;
                                    top: <?= $row['css_top'] ?: 0 ?>%;
                                    max-width: none;
                                    object-fit: cover;
                                " alt="<?= htmlspecialchars($row['judul']) ?>">
                            </div>

                            <div class="card-body">
                                <h3><?= htmlspecialchars($row['judul']) ?></h3>
                                <p><?= htmlspecialchars($row['ringkasan']) ?></p>
                                <a href="artikel.php?id=<?= $row['id'] ?>" class="cta-link">Call to action &rarr;</a>
                            </div>
                        </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center; width:100%; grid-column: 1 / -1;">Belum ada destinasi yang ditambahkan. Silakan login ke panel Admin.</p>
            <?php endif; ?>

        </div>
    </section>

    <!-- BAGIAN FAQ (DINAMIS DARI DATABASE) -->
    <section class="faq-section">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 20px;">Pertanyaan Sering Diajukan (FAQ)</h2>
            
            <?php if (count($faq_data) > 0): ?>
                <?php foreach ($faq_data as $faq): ?>
                    <div class="faq-item">
                        <button class="faq-question"><?= htmlspecialchars($faq['pertanyaan']) ?></button>
                        <div class="faq-answer">
                            <!-- nl2br digunakan agar baris baru di database (enter) menjadi tag <br> di HTML -->
                            <p><?= nl2br(htmlspecialchars($faq['jawaban'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center;">Belum ada FAQ yang ditambahkan.</p>
            <?php endif; ?>

        </div>
    </section>

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

    <script src="/script.js"></script>

</body>
</html>