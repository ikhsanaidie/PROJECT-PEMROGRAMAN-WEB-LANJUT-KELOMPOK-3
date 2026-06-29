<?php
// guru/reset_password.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';

$nip = $_GET['id'] ?? '';
if ($nip) {
    $query = "UPDATE tbl_guru SET password = MD5('guru123') WHERE nip = '$nip'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = 'Password berhasil direset ke default (guru123)';
    } else {
        $_SESSION['error'] = 'Gagal reset password: ' . mysqli_error($conn);
    }
}
header('Location: index.php');
exit;
?>