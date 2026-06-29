<?php
// laporan/absensi.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

$kelas = $_GET['kelas'] ?? '';
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');
$mode = $_GET['mode'] ?? 'harian'; // harian atau bulanan

$kelasList = mysqli_query($conn, "SELECT nama_kelas FROM tbl_kelas ORDER BY nama_kelas");
$waliKelas = '-';

if ($kelas) {
    $wk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT wali_kelas FROM tbl_kelas WHERE nama_kelas = '$kelas'"));
    if ($wk) {
        $g = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_guru FROM tbl_guru WHERE nip = '{$wk['wali_kelas']}'"));
        $waliKelas = $g['nama_guru'] ?? '-';
    }
}

$siswaList = [];
if ($kelas) {
    $siswaList = mysqli_query($conn, "SELECT * FROM tbl_siswa WHERE kelas = '$kelas' ORDER BY nama");
}

$total = mysqli_num_rows($siswaList);
?>
<div class="page-header">
    <h2>Laporan Absensi Siswa</h2>
    <div>
        <a href="absensi_pdf.php?kelas=<?php echo $kelas; ?>&tanggal=<?php echo $tanggal; ?>&mode=harian" class="btn btn-primary" target="_blank">📄 Cetak PDF Harian</a>
        <a href="absensi_pdf.php?kelas=<?php echo $kelas; ?>&bulan=<?php echo $bulan; ?>&tahun=<?php echo $tahun; ?>&mode=bulanan" class="btn btn-success" target="_blank">📄 Cetak PDF Rekap Bulanan</a>
    </div>
</div>

<div class="filter-bar">
    <form method="GET" style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;width:100%;">
        <label>Kelas:</label>
        <select name="kelas" class="form-control" style="width:150px;">
            <option value="">-- Pilih Kelas --</option>
            <?php while($k = mysqli_fetch_assoc($kelasList)): ?>
            <option value="<?php echo $k['nama_kelas']; ?>" <?php echo $kelas == $k['nama_kelas'] ? 'selected' : ''; ?>><?php echo $k['nama_kelas']; ?></option>
            <?php endwhile; ?>
        </select>
        
        <label>Mode:</label>
        <select name="mode" class="form-control" style="width:120px;">
            <option value="harian" <?php echo $mode == 'harian' ? 'selected' : ''; ?>>Harian</option>
            <option value="bulanan" <?php echo $mode == 'bulanan' ? 'selected' : ''; ?>>Bulanan</option>
        </select>
        
        <label>Tanggal:</label>
        <input type="date" name="tanggal" class="form-control" value="<?php echo $tanggal; ?>" style="width:150px;">
        
        <label>Bulan:</label>
        <select name="bulan" class="form-control" style="width:120px;">
            <?php
            $bulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            foreach ($bulanList as $i => $b):
                $val = str_pad($i+1, 2, '0', STR_PAD_LEFT);
            ?>
            <option value="<?php echo $val; ?>" <?php echo $bulan == $val ? 'selected' : ''; ?>><?php echo $b; ?></option>
            <?php endforeach; ?>
        </select>
        
        <label>Tahun:</label>
        <select name="tahun" class="form-control" style="width:100px;">
            <?php for($y = date('Y')-3; $y <= date('Y'); $y++): ?>
            <option value="<?php echo $y; ?>" <?php echo $tahun == $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
            <?php endfor; ?>
        </select>
        
        <button type="submit" class="btn btn-primary">TAMPILKAN</button>
        <a href="absensi.php" class="btn btn-secondary">RESET</a>
    </form>
</div>

<?php if ($kelas && $siswaList && mysqli_num_rows($siswaList) > 0): ?>
<div class="notification notification-info">
    <strong>Kelas:</strong> <?php echo $kelas; ?> &nbsp;|&nbsp;
    <strong>Wali Kelas:</strong> <?php echo $waliKelas; ?> &nbsp;|&nbsp;
    <strong>Total Siswa:</strong> <?php echo $total; ?>
    <?php if ($mode == 'harian'): ?>
    &nbsp;|&nbsp; <strong>Tanggal:</strong> <?php echo $tanggal; ?>
    <?php else: ?>
    &nbsp;|&nbsp; <strong>Periode:</strong> <?php echo date('F', mktime(0,0,0,$bulan,1)); ?> <?php echo $tahun; ?>
    <?php endif; ?>
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Wali Kelas</th>
                <?php if ($mode == 'harian'): ?>
                <th>Status</th>
                <?php else: ?>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alpa</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($siswaList)):
                if ($mode == 'harian') {
                    // Mode Harian
                    $status = '-';
                    $abs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM tbl_absensi WHERE tanggal = '$tanggal' AND nisn = '{$row['nisn']}'"));
                    if ($abs) $status = $abs['status'];
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['nisn']; ?></td>
                        <td><?php echo $row['nama']; ?></td>
                        <td><?php echo $row['kelas']; ?></td>
                        <td><?php echo $waliKelas; ?></td>
                        <td>
                            <?php
                            $bg = $status == 'Hadir' ? 'green' : ($status == 'Sakit' ? 'orange' : ($status == 'Izin' ? 'blue' : ($status == 'Alpa' ? 'red' : 'gray')));
                            echo "<span style='background:$bg;color:white;padding:2px 10px;border-radius:4px;font-size:11px;'>$status</span>";
                            ?>
                        </td>
                    </tr>
                    <?php
                } else {
                    // Mode Bulanan
                    $tglAwal = $tahun . '-' . $bulan . '-01';
                    $tglAkhir = $tahun . '-' . $bulan . '-' . date('t', strtotime($tglAwal));
                    
                    $hadir = 0; $sakit = 0; $izin = 0; $alpa = 0;
                    $absList = mysqli_query($conn, "SELECT status FROM tbl_absensi WHERE nisn = '{$row['nisn']}' AND tanggal BETWEEN '$tglAwal' AND '$tglAkhir'");
                    while ($a = mysqli_fetch_assoc($absList)) {
                        if ($a['status'] == 'Hadir') $hadir++;
                        elseif ($a['status'] == 'Sakit') $sakit++;
                        elseif ($a['status'] == 'Izin') $izin++;
                        elseif ($a['status'] == 'Alpa') $alpa++;
                    }
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['nisn']; ?></td>
                        <td><?php echo $row['nama']; ?></td>
                        <td><?php echo $row['kelas']; ?></td>
                        <td><?php echo $waliKelas; ?></td>
                        <td><?php echo $hadir; ?></td>
                        <td><?php echo $sakit; ?></td>
                        <td><?php echo $izin; ?></td>
                        <td><?php echo $alpa; ?></td>
                    </tr>
                    <?php
                }
            endwhile;
            ?>
        </tbody>
    </table>
</div>
<?php elseif ($kelas): ?>
<div class="notification notification-warning">Tidak ada siswa di kelas <?php echo $kelas; ?></div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>