<?php
// guru/hapus.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
/** @var mysqli $conn */

$nip = $_GET['id'] ?? '';
if ($nip) {
    // Cek apakah guru digunakan sebagai wali kelas
    $cek = mysqli_query($conn, "SELECT * FROM tbl_kelas WHERE wali_kelas = '$nip'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = 'Guru tidak bisa dihapus karena masih menjadi wali kelas!';
    } else {
        mysqli_query($conn, "DELETE FROM tbl_guru WHERE nip = '$nip'");
        $_SESSION['success'] = 'Data guru berhasil dihapus!';
    }
}
header('Location: index.php');
exit;
?>