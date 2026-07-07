<?php
// config/koneksi.php
$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$dbname = getenv('DB_NAME') ?: 'db_siakad';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    $fallbackHost = 'sql202.infinityfree.com';
    $fallbackUser = 'if0_42346917';
    $fallbackPass = 'M8mpOrCLefaI';
    $fallbackDbname = 'if0_42346917_db_siakad';

    $conn = mysqli_connect($fallbackHost, $fallbackUser, $fallbackPass, $fallbackDbname);
}

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