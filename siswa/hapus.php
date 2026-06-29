<?php
// siswa/hapus.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';

$nisn = $_GET['id'] ?? '';
if ($nisn) {
    mysqli_query($conn, "DELETE FROM tbl_siswa WHERE nisn = '$nisn'");
}
header('Location: index.php');
exit;
?>