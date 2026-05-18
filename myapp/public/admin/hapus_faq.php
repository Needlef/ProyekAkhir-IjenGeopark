<?php
session_start();
require '../includes/koneksi.php';

if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM faq WHERE id = :id");
    $stmt->execute(['id' => $_GET['id']]);
}

header("Location: kelola_faq.php");
exit;