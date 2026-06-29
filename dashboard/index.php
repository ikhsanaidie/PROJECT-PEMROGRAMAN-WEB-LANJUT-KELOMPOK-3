<?php
// dashboard/index.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

// Hitung statistik
$total_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_siswa"))['total'];
$total_guru = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_guru"))['total'];
$total_kelas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_kelas"))['total'];
$total_laki = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_siswa WHERE jk = 'Laki-laki'"))['total'];
$total_perempuan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_siswa WHERE jk = 'Perempuan'"))['total'];

// Tunggakan
$tunggakan = mysqli_query($conn, "SELECT SUM(sisa_tagihan) as total, COUNT(*) as siswa FROM tbl_pembayaran WHERE status != 'Lunas' AND sisa_tagihan > 0");
$tunggakan_data = mysqli_fetch_assoc($tunggakan);
$total_tunggakan = $tunggakan_data['total'] ?? 0;
$siswa_tunggakan = $tunggakan_data['siswa'] ?? 0;
?>
<div class="page-header">
    <h2>Dashboard</h2>
    <p>Selamat datang, <?php echo $_SESSION['nama']; ?>!</p>
</div>

<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon">👨‍🎓</div>
        <div class="stat-info">
            <div class="stat-value"><?php echo $total_siswa; ?></div>
            <div class="stat-label">Total Siswa</div>
        </div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">👨</div>
        <div class="stat-info">
            <div class="stat-value"><?php echo $total_laki; ?></div>
            <div class="stat-label">Laki-laki</div>
        </div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon">👩</div>
        <div class="stat-info">
            <div class="stat-value"><?php echo $total_perempuan; ?></div>
            <div class="stat-label">Perempuan</div>
        </div>
    </div>
    <div class="stat-card orange">
        <div class="stat-icon">🏫</div>
        <div class="stat-info">
            <div class="stat-value"><?php echo $total_kelas; ?></div>
            <div class="stat-label">Total Kelas</div>
        </div>
    </div>
</div>

<?php if ($siswa_tunggakan > 0): ?>
<div class="notification notification-warning">
    ⚠️ Terdapat <?php echo $siswa_tunggakan; ?> siswa dengan total tunggakan <?php echo number_format($total_tunggakan, 0, ',', '.'); ?>. Segera lakukan pembayaran!
</div>
<?php else: ?>
<div class="notification notification-success">
    ✅ Semua siswa sudah lunas! Tidak ada tunggakan pembayaran SPP.
</div>
<?php endif; ?>

<div class="card">
    <div class="card-title">ℹ️ Informasi Sistem</div>
    <p style="font-size:13px;color:#555;line-height:1.8;">
        Sistem Informasi Akademik SMA PGRI 4 Jakarta<br><br>
        Fitur yang tersedia:<br>
        • Master Data (Siswa, Guru, Kelas)<br>
        • Transaksi (Input Nilai, Absensi, Pembayaran SPP)<br>
        • Laporan (Siswa, Guru, Nilai, Absensi)<br>
        • Cetak PDF dengan format surat resmi<br>
        • Cetak Raport Otomatis<br>
        • Kalender Akademik<br><br>
        Gunakan menu di samping kiri untuk mengakses fitur.
    </p>
</div>

<?php include '../includes/footer.php'; ?>