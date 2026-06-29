<?php
// nilai/hapus.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
/** @var mysqli $conn */

$nisn = $_GET['nisn'] ?? '';
$mapel = $_GET['mapel'] ?? '';
if ($nisn && $mapel) {
    mysqli_query($conn, "DELETE FROM tbl_nilai WHERE nisn = '$nisn' AND mata_pelajaran = '$mapel'");
}
header('Location: index.php');
exit;
?>