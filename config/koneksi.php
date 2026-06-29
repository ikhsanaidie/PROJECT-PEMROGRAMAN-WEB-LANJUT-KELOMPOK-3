<?php
// config/koneksi.php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'db_siakad';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($conn, "utf8");

// Mulai session jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>