<?php
require 'includes/koneksi.php';

$username = 'admin';
$password_asli = 'admin123';
// Mengacak password menggunakan algoritma bcrypt standar industri
$password_hash = password_hash($password_asli, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO admin_users (username, password) VALUES (:user, :pass)");
$stmt->execute(['user' => $username, 'pass' => $password_hash]);

echo "Admin berhasil dibuat! Username: $username | Password: $password_asli";
?>