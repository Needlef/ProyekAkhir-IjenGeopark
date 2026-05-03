<?php
// 1. Konfigurasi Kredensial Database
$host     = 'localhost'; 
$dbname   = 'db_ijen_geopark'; 
$username = 'root';      // Default XAMPP/Laragon
$password = '';          // Kosongkan untuk default XAMPP/Laragon

// 2. Alur Pembentukan Koneksi
try {
    // Membuat instance PDO baru (membangun jembatan)
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    
    // Mengatur mode error agar PDO melempar Exception jika terjadi masalah query
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Jika perlu testing, uncomment baris di bawah ini:
     //echo "Koneksi ke database berhasil!";

} catch (PDOException $e) {
    // Menangkap error jika jembatan gagal dibangun (misal: database belum dibuat atau XAMPP belum nyala)
    // Menghentikan eksekusi kode selanjutnya dan menampilkan pesan error
    die("Koneksi database gagal: " . $e->getMessage());
}
?>