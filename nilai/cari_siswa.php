<?php
// nilai/cari_siswa.php
include '../config/koneksi.php';
/** @var mysqli $conn */

$nisn = $_GET['nisn'] ?? '';
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama, kelas FROM tbl_siswa WHERE nisn = '$nisn'"));

if ($data) {
    echo json_encode(['success' => true, 'nama' => $data['nama'], 'kelas' => $data['kelas']]);
} else {
    echo json_encode(['success' => false]);
}
?>