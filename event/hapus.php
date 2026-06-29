<?php
// event/hapus.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';

$id = $_GET['id'] ?? '';
if ($id) {
    mysqli_query($conn, "DELETE FROM tbl_event WHERE id_event = '$id'");
}
header('Location: index.php');
exit;
?>