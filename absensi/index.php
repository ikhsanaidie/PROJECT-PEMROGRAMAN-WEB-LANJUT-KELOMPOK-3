<?php
// absensi/index.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

$kelas = $_GET['kelas'] ?? '';
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$siswaList = [];
$waliKelas = '-';

if ($kelas) {
    // Ambil wali kelas
    $wk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT wali_kelas FROM tbl_kelas WHERE nama_kelas = '$kelas'"));
    if ($wk) {
        $g = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_guru FROM tbl_guru WHERE nip = '{$wk['wali_kelas']}'"));
        $waliKelas = $g['nama_guru'] ?? '-';
    }
    
    $siswaList = mysqli_query($conn, "SELECT * FROM tbl_siswa WHERE kelas = '$kelas' ORDER BY nama");
}

$kelasList = mysqli_query($conn, "SELECT nama_kelas FROM tbl_kelas ORDER BY nama_kelas");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'] ?? '';
    $kelas = $_POST['kelas'] ?? '';
    
    // Hapus absensi lama
    mysqli_query($conn, "DELETE FROM tbl_absensi WHERE tanggal = '$tanggal' AND kelas = '$kelas'");
    
    // Simpan absensi baru
    $statusList = $_POST['status'] ?? [];
    $nisnList = $_POST['nisn'] ?? [];
    $namaList = $_POST['nama'] ?? [];
    
    foreach ($nisnList as $i => $nisn) {
        $status = $statusList[$i] ?? 'Hadir';
        $nama = $namaList[$i] ?? '';
        if ($nisn) {
            $query = "INSERT INTO tbl_absensi (tanggal, kelas, nisn, nama_siswa, status) 
                      VALUES ('$tanggal', '$kelas', '$nisn', '$nama', '$status')";
            mysqli_query($conn, $query);
        }
    }
    
    echo '<div class="notification notification-success">✅ Absensi berhasil disimpan!</div>';
}
?>
<div class="page-header">
    <h2>Absensi Siswa</h2>
</div>

<form method="GET" class="filter-bar">
    <label>Kelas:</label>
    <select name="kelas" class="form-control" style="width:180px;" onchange="this.form.submit()">
        <option value="">-- Pilih Kelas --</option>
        <?php while($k = mysqli_fetch_assoc($kelasList)): ?>
        <option value="<?php echo $k['nama_kelas']; ?>" <?php echo $kelas == $k['nama_kelas'] ? 'selected' : ''; ?>><?php echo $k['nama_kelas']; ?></option>
        <?php endwhile; ?>
    </select>
    <label>Tanggal:</label>
    <input type="date" name="tanggal" class="form-control" value="<?php echo $tanggal; ?>" style="width:180px;" onchange="this.form.submit()">
    <button type="submit" class="btn btn-primary">TAMPILKAN</button>
</form>

<?php if ($kelas && mysqli_num_rows($siswaList) > 0): ?>
<div class="card">
    <div class="card-title">
        Absensi Kelas <?php echo $kelas; ?> 
        <span style="font-size:12px;font-weight:normal;color:#666;">| Wali Kelas: <?php echo $waliKelas; ?></span>
    </div>
    <form method="POST">
        <input type="hidden" name="tanggal" value="<?php echo $tanggal; ?>">
        <input type="hidden" name="kelas" value="<?php echo $kelas; ?>">
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($siswaList)):
                    // Cek status existing
                    $status = 'Hadir';
                    $cek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM tbl_absensi WHERE tanggal = '$tanggal' AND kelas = '$kelas' AND nisn = '{$row['nisn']}'"));
                    if ($cek) $status = $cek['status'];
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['nisn']; ?></td>
                        <td><?php echo $row['nama']; ?></td>
                        <td>
                            <input type="hidden" name="nisn[]" value="<?php echo $row['nisn']; ?>">
                            <input type="hidden" name="nama[]" value="<?php echo $row['nama']; ?>">
                            <select name="status[]" class="form-control" style="width:150px;">
                                <option value="Hadir" <?php echo $status == 'Hadir' ? 'selected' : ''; ?>>Hadir</option>
                                <option value="Sakit" <?php echo $status == 'Sakit' ? 'selected' : ''; ?>>Sakit</option>
                                <option value="Izin" <?php echo $status == 'Izin' ? 'selected' : ''; ?>>Izin</option>
                                <option value="Alpa" <?php echo $status == 'Alpa' ? 'selected' : ''; ?>>Alpa</option>
                                <option value="Tidak Hadir" <?php echo $status == 'Tidak Hadir' ? 'selected' : ''; ?>>Tidak Hadir</option>
                            </select>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div style="margin-top:15px;">
            <button type="submit" class="btn btn-success">💾 SIMPAN ABSENSI</button>
        </div>
    </form>
</div>
<?php elseif ($kelas): ?>
<div class="notification notification-warning">Tidak ada siswa di kelas <?php echo $kelas; ?></div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>