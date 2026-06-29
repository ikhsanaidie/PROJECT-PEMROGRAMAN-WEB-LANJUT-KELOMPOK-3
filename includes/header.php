<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD - SMA PGRI 4 Jakarta</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/img/smapgri4.png">
</head>
<body>
    <div class="app-container">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-left">
                <img src="../assets/img/smapgri4.png" alt="Logo" class="logo-small">
                <span class="app-title">SIAKAD - SMA PGRI 4 JAKARTA</span>
            </div>
            <div class="navbar-right">
                <span class="clock" id="clock"></span>
                <span class="user-name"><?php echo $_SESSION['nama'] ?? 'Guest'; ?></span>
                <a href="../auth/logout.php" class="btn-logout">LOGOUT</a>
            </div>
        </nav>

        <div class="main-content">
            <!-- Sidebar -->
            <div class="sidebar">
                <?php include 'sidebar.php'; ?>
            </div>

            <!-- Content -->
            <div class="content">
                <!-- Tampilkan notifikasi jika ada -->
                <?php if (isset($_SESSION['success'])): ?>
                <div class="notification notification-success">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                <div class="notification notification-danger">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
                <?php endif; ?>