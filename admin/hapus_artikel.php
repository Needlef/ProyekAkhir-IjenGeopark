<?php
session_start();
require '../includes/koneksi.php';

if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // 1. Cari nama gambar sebelum datanya dihapus
    $stmt = $pdo->prepare("SELECT gambar FROM artikel WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $artikel = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($artikel) {
        $gambar = $artikel['gambar'];
        
        // 2. Jika gambar BUKAN berasal dari link luar (tidak mengandung http)
        // DAN file fisiknya benar-benar ada di folder uploads, maka hapus filenya
        if (strpos($gambar, 'http') !== 0 && file_exists("../uploads/" . $gambar)) {
            unlink("../uploads/" . $gambar);
        }
        
        // 3. Hapus baris data dari database
        $del_stmt = $pdo->prepare("DELETE FROM artikel WHERE id = :id");
        $del_stmt->execute(['id' => $id]);
    }
}

header("Location: kelola_artikel.php");
exit;