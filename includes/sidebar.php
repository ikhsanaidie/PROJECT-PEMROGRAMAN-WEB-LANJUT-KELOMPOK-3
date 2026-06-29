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
        <span class="menu-icon">📊</span> DASHBOARD
    </a>

    <!-- PROFIL SEKOLAH (di bawah dashboard) -->
    <a href="../profil/" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'profil') !== false ? 'active' : ''; ?>">
        <span class="menu-icon">🏫</span> PROFIL SEKOLAH
    </a>

    <?php if ($role === 'admin'): ?>
    <div class="menu-category">
        <div class="category-header">DATA MASTER</div>
        <a href="../siswa/" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'siswa') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">👨‍🎓</span> Data Siswa
        </a>
        <a href="../guru/" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'guru') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">👨‍🏫</span> Data Guru
        </a>
        <a href="../kelas/" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'kelas') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">🏫</span> Data Kelas
        </a>
    </div>
    <?php endif; ?>

    <?php if ($role === 'admin' || $role === 'guru'): ?>
    <div class="menu-category">
        <div class="category-header">TRANSAKSI</div>
        <a href="../absensi/" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'absensi') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">📅</span> Absensi
        </a>
        <a href="../nilai/" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'nilai') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">📊</span> Input Nilai
        </a>
        <?php if ($role === 'admin'): ?>
        <a href="../pembayaran/" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'pembayaran') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">💰</span> Pembayaran SPP
        </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if ($role === 'admin' || $role === 'guru' || $role === 'kepsek'): ?>
    <div class="menu-category">
        <div class="category-header">REPORT</div>
        <a href="../laporan/siswa.php" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'laporan/siswa') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">📋</span> Laporan Siswa
        </a>
        <?php if ($role === 'admin' || $role === 'kepsek'): ?>
        <a href="../laporan/guru.php" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'laporan/guru') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">👨‍🏫</span> Laporan Guru
        </a>
        <?php endif; ?>
        <a href="../laporan/nilai.php" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'laporan/nilai') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">📊</span> Laporan Nilai
        </a>
        <a href="../laporan/absensi.php" class="menu-item sub-menu <?php echo strpos($_SERVER['REQUEST_URI'], 'laporan/absensi') !== false ? 'active' : ''; ?>">
            <span class="menu-icon">📅</span> Laporan Absensi
        </a>
    </div>
    <?php endif; ?>

    <?php if ($role === 'admin' || $role === 'guru'): ?>
    <a href="../laporan/raport.php" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'laporan/raport') !== false ? 'active' : ''; ?>">
        <span class="menu-icon">📑</span> Cetak Raport
    </a>
    <?php endif; ?>

    <a href="../event/" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'event') !== false ? 'active' : ''; ?>">
        <span class="menu-icon">📅</span> Kalender Akademik
    </a>

    <a href="../ganti_password/" class="menu-item <?php echo strpos($_SERVER['REQUEST_URI'], 'ganti_password') !== false ? 'active' : ''; ?>">
        <span class="menu-icon">🔐</span> Ganti Password
    </a>
</div>