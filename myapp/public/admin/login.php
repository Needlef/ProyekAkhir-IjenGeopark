<?php
// 1. Mulai Sesi (Membangunkan Penjaga)
session_start();

// 2. Panggil Jembatan Database
require '../includes/koneksi.php';

// 3. Cek apakah sudah punya tiket (Sudah Login)
// Jika iya, tidak perlu lihat form login lagi, langsung usir ke Dashboard
if (isset($_SESSION['status_login']) && $_SESSION['status_login'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

// 4. Alur ketika tombol "Masuk" ditekan (Metode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_user = $_POST['username'];
    $input_pass = $_POST['password'];

    // A. Cari username di database menggunakan Prepared Statement (Anti SQL Injection)
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $input_user]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // B. Logika Verifikasi
    // Jika username ketemu AND password yang diketik cocok dengan hash di database
    if ($user_data && password_verify($input_pass, $user_data['password'])) {
        
        // C. Buat Tiket Sesi!
        $_SESSION['status_login'] = true;
        $_SESSION['admin_name'] = $user_data['username'];
        
        // D. Buka pintu ke Dashboard
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Ijen Geopark</title>
    <style>
        /* Gaya sederhana agar form tidak terlalu polos */
        body { font-family: sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 350px; }
        .login-box h2 { text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 14px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #333; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #555; }
        .error-msg { color: red; font-size: 14px; text-align: center; margin-bottom: 15px; }
    </style>
</head>
<body>

    <div class="login-box">
        <h2>Panel Admin</h2>
        
        <!-- Menampilkan pesan error jika login gagal -->
        <?php if ($error): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Masuk</button>
        </form>
    </div>

</body>
</html>