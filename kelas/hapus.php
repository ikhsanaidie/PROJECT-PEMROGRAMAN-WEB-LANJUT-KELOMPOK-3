<?php
// kelas/hapus.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';

$id = $_GET['id'] ?? '';
if ($id) {
    mysqli_query($conn, "DELETE FROM tbl_kelas WHERE id_kelas = '$id'");
}
header('Location: index.php');
exit;
?>