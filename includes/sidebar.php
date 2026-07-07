<?php
// includes/sidebar.php
$role = $_SESSION['role'] ?? null;
?>
<div class="sidebar-menu">
    <div class="profile-section">
        <div class="avatar">👤</div>
        <div class="user-role"><?php echo $role === 'admin' ? 'Administrator' : ($role === 'guru' ? 'Guru' : 'Guest'); ?></div>
        <div class="user-name-sidebar"><?php echo $_SESSION['nama'] ?? 'SMA PGRI 4 Jakarta'; ?></div>
    </div>
    <hr>

    <a href="../dashboard/" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : ''; ?>">
        <span class="menu-icon">📊</span><span class="menu-text"> DASHBOARD</span>
    </a>

    <!-- PROFIL SEKOLAH (di bawah dashboard) -->
    <a href="../profil/" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'profil') !== false ? 'active' : ''; ?>">
        <span class="menu-icon">🏫</span><span class="menu-text"> PROFIL SEKOLAH</span>
    </a>

    <?php if ($role === 'admin'): ?>
    <div class="menu-category">
        <div class="category-header">DATA MASTER</div>
        <a href="../siswa/" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'siswa') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">👨‍🎓</span><span class="menu-text"> Data Siswa</span>
        </a>
        <a href="../guru/" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'guru') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">👨‍🏫</span><span class="menu-text"> Data Guru</span>
        </a>
        <a href="../kelas/" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'kelas') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">🏫</span><span class="menu-text"> Data Kelas</span>
        </a>
    </div>
    <?php endif; ?>

    <?php if ($role === 'admin' || $role === 'guru'): ?>
    <div class="menu-category">
        <div class="category-header">TRANSAKSI</div>
        <a href="../absensi/" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'absensi') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">📅</span><span class="menu-text"> Absensi</span>
        </a>
        <a href="../nilai/" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'nilai') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">📊</span><span class="menu-text"> Input Nilai</span>
        </a>
        <?php if ($role === 'admin'): ?>
        <a href="../pembayaran/" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'pembayaran') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">💰</span><span class="menu-text"> Pembayaran SPP</span>
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($role === 'admin' || $role === 'guru' || $role === 'kepsek'): ?>
    <div class="menu-category">
        <div class="category-header">REPORT</div>
        <a href="../laporan/siswa.php" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'laporan/siswa') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">📋</span><span class="menu-text"> Laporan Siswa</span>
        </a>
        <?php if ($role === 'admin' || $role === 'kepsek'): ?>
        <a href="../laporan/guru.php" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'laporan/guru') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">👨‍🏫</span><span class="menu-text"> Laporan Guru</span>
        </a>
        <?php endif; ?>
        <a href="../laporan/nilai.php" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'laporan/nilai') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">📊</span><span class="menu-text"> Laporan Nilai</span>
        </a>
        <a href="../laporan/absensi.php" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'laporan/absensi') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">📅</span><span class="menu-text"> Laporan Absensi</span>
        </a>
    </div>
    <?php endif; ?>

    <?php if ($role === 'admin' || $role === 'guru'): ?>
    <a href="../laporan/raport.php" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'laporan/raport') !== false ? 'active' : ''; ?>">
        <span class="menu-icon">📑</span><span class="menu-text"> Cetak Raport</span>
    </a>
    <?php endif; ?>

    <a href="../event/" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'event') !== false ? 'active' : ''; ?>">
        <span class="menu-icon">📅</span><span class="menu-text"> Kalender Akademik</span>
    </a>

    <a href="../ganti_password/" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'ganti_password') !== false ? 'active' : ''; ?>">
        <span class="menu-icon">🔐</span><span class="menu-text"> Ganti Password</span>
    </a>
</div>