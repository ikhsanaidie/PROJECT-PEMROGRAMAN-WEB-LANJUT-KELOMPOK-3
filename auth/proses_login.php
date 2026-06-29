<?php
// auth/proses_login.php
session_start();
include '../config/koneksi.php';

$role = $_POST['role'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    header('Location: login.php?error=1');
    exit;
}

if ($role === 'Guru') {
    // Login Guru
    $query = "SELECT nip, nama_guru, mapel FROM tbl_guru WHERE nip = ? AND password = MD5(?) AND status = 'aktif'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['nip'] = $row['nip'];
        $_SESSION['nama'] = $row['nama_guru'];
        $_SESSION['mapel'] = $row['mapel'];
        $_SESSION['role'] = 'guru';
        header('Location: ../dashboard/');
        exit;
    }
} else if ($role === 'Administrator') {
    // Login Admin
    $query = "SELECT username, nama_lengkap, role FROM tbl_user WHERE username = ? AND password = MD5(?) AND status = 'aktif'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['nama'] = $row['nama_lengkap'];
        $_SESSION['role'] = $row['role'];
        header('Location: ../dashboard/');
        exit;
    }
}

// Login gagal
header('Location: login.php?error=1');
exit;
?>