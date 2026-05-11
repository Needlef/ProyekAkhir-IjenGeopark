<?php
// 1. Panggil Penjaga Sesi!
session_start();

// 2. Alur Pengecekan Keamanan (Logic Flow)
// Mengecek apakah di dalam memori server terdapat array $_SESSION dengan key 'status_login'
// DAN apakah nilainya benar-benar boolean (true).
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    // Jika salah satu kondisi tidak terpenuhi (artinya user belum login),
    // Alur eksekusi akan langsung dilempar kembali ke login.php
    header("Location: login.php");
    exit; // Menghentikan eksekusi kode di bawahnya agar HTML tidak sempat dimuat
}

// Mengambil nama admin dari sesi untuk ditampilkan
$nama_admin = $_SESSION['admin_name'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Ijen Geopark</title>
    <style>
        body { font-family: sans-serif; margin: 0; background: #f4f4f4; display: flex; }
        .sidebar { width: 250px; background: #222; color: white; height: 100vh; padding: 20px; box-sizing: border-box; }
        .sidebar h2 { margin-top: 0; font-size: 20px; border-bottom: 1px solid #444; padding-bottom: 10px; }
        .sidebar a { display: block; color: #ddd; text-decoration: none; padding: 10px 0; border-bottom: 1px solid #333; }
        .sidebar a:hover { color: white; padding-left: 5px; transition: 0.3s; }
        .content { flex: 1; padding: 30px; }
        .card { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .logout-btn { color: #ff4d4d !important; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>

    <!-- Navigasi Samping -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="kelola_artikel.php">📝 Kelola Artikel</a>
        <a href="kelola_faq.php">❓ Kelola FAQ</a>
        <a href="logout.php" class="logout-btn"> Keluar (Logout)</a>
    </div>

    <!-- Konten Utama -->
    <div class="content">
        <div class="card">
            <h1>Selamat Datang, <?= htmlspecialchars($nama_admin) ?>!</h1>
            <p>Ini adalah pusat kendali sistem manajemen konten (CMS) Ijen Geopark.</p>
            <p>Silakan gunakan menu di sebelah kiri untuk menambah, mengubah, atau menghapus data yang akan ditampilkan di halaman utama pengunjung.</p>
        </div>
    </div>

</body>
</html>