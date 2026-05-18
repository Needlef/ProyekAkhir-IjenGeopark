<?php
// 1. Panggil sesi yang sedang aktif
session_start();

// 2. Kosongkan semua data di dalam array $_SESSION (status_login, admin_name, dll)
session_unset();

// 3. Hancurkan file sesi secara fisik di dalam server
session_destroy();

// 4. Arahkan kembali (Redirect) ke halaman pintu gerbang
header("Location: login.php");
exit;
?>