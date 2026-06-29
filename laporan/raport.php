<?php
// laporan/raport.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';
/** @var mysqli $conn */

$nisn = $_GET['nisn'] ?? '';
$semester = $_GET['semester'] ?? 'Ganjil';
$tahun = $_GET['tahun'] ?? date('Y') . '/' . (date('Y')+1);
$data_siswa = null;
$nilaiList = [];
$absenList = [];

if ($nisn) {
    $data_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_siswa WHERE nisn = '$nisn'"));
    if ($data_siswa) {
        // Ambil nilai
        $nilaiList = mysqli_query($conn, "SELECT * FROM tbl_nilai WHERE nisn = '$nisn' ORDER BY mata_pelajaran");
        
        // Ambil absensi 6 bulan terakhir
        $sixMonthsAgo = date('Y-m-d', strtotime('-6 months'));
        $absenList = mysqli_query($conn, "SELECT status FROM tbl_absensi WHERE nisn = '$nisn' AND tanggal >= '$sixMonthsAgo'");
    }
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = $_POST['nisn'] ?? '';
    $semester = $_POST['semester'] ?? 'Ganjil';
    $tahun = $_POST['tahun'] ?? date('Y') . '/' . (date('Y')+1);
    header('Location: raport.php?nisn=' . $nisn . '&semester=' . $semester . '&tahun=' . $tahun);
    exit;
}
?>
<div class="page-header">
    <h2>Cetak Raport</h2>
    <?php if ($data_siswa): ?>
    <a href="raport_pdf.php?nisn=<?php echo $nisn; ?>&semester=<?php echo $semester; ?>&tahun=<?php echo $tahun; ?>" class="btn btn-success" target="_blank">📑 Cetak Raport PDF</a>
    <?php endif; ?>
</div>

<div class="card">
    <form method="POST">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;">
            <div class="form-group">
                <label>NISN Siswa</label>
                <input type="text" name="nisn" class="form-control" value="<?php echo $nisn; ?>" placeholder="Masukkan NISN" required>
            </div>
            <div class="form-group">
                <label>Semester</label>
                <select name="semester" class="form-control">
                    <option value="Ganjil" <?php echo $semester == 'Ganjil' ? 'selected' : ''; ?>>Ganjil</option>
                    <option value="Genap" <?php echo $semester == 'Genap' ? 'selected' : ''; ?>>Genap</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tahun Ajaran</label>
                <select name="tahun" class="form-control">
                    <?php for($i = 2022; $i <= date('Y'); $i++): ?>
                    <option value="<?php echo $i . '/' . ($i+1); ?>" <?php echo $tahun == $i . '/' . ($i+1) ? 'selected' : ''; ?>><?php echo $i . '/' . ($i+1); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        <div style="margin-top:15px;">
            <button type="submit" class="btn btn-primary">🔍 TAMPILKAN</button>
            <a href="raport.php" class="btn btn-secondary">RESET</a>
        </div>
    </form>
</div>

<?php if ($data_siswa): ?>
<div class="card">
    <div class="card-title">Data Siswa</div>
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:15px;">
        <div><strong>Nama:</strong> <?php echo $data_siswa['nama']; ?></div>
        <div><strong>NISN:</strong> <?php echo $data_siswa['nisn']; ?></div>
        <div><strong>Kelas:</strong> <?php echo $data_siswa['kelas']; ?></div>
    </div>
    
    <?php
    // Hitung statistik absensi
    $sakit = 0; $izin = 0; $alpa = 0;
    while ($a = mysqli_fetch_assoc($absenList)) {
        if ($a['status'] == 'Sakit') $sakit++;
        elseif ($a['status'] == 'Izin') $izin++;
        elseif ($a['status'] == 'Alpa') $alpa++;
    }
    ?>
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:15px;background:#f5f5f5;padding:10px;border-radius:8px;">
        <div><strong>Sakit:</strong> <?php echo $sakit; ?> hari</div>
        <div><strong>Izin:</strong> <?php echo $izin; ?> hari</div>
        <div><strong>Alpa:</strong> <?php echo $alpa; ?> hari</div>
    </div>
    
    <div class="card-title" style="margin-top:15px;">Nilai Siswa</div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Tugas</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Nilai Akhir</th>
                    <th>Predikat</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $totalNilai = 0;
                $count = 0;
                while ($row = mysqli_fetch_assoc($nilaiList)):
                    $totalNilai += $row['nilai_akhir'];
                    $count++;
                    $predikat = $row['nilai_akhir'] >= 90 ? 'A' : ($row['nilai_akhir'] >= 80 ? 'A-' : ($row['nilai_akhir'] >= 70 ? 'B+' : ($row['nilai_akhir'] >= 60 ? 'B' : 'C')));
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['mata_pelajaran']; ?></td>
                    <td><?php echo $row['guru_pengajar']; ?></td>
                    <td><?php echo $row['tugas']; ?></td>
                    <td><?php echo $row['uts']; ?></td>
                    <td><?php echo $row['uas']; ?></td>
                    <td><strong><?php echo number_format($row['nilai_akhir'], 2); ?></strong></td>
                    <td><strong><?php echo $predikat; ?></strong></td>
                </tr>
                <?php endwhile; ?>
                <?php if ($count == 0): ?>
                <tr><td colspan="8" style="text-align:center;">Belum ada data nilai</td></tr>
                <?php else: ?>
                <tr style="background:#f0f0f0;font-weight:bold;">
                    <td colspan="6" style="text-align:right;">RATA-RATA</td>
                    <td><?php echo number_format($totalNilai/$count, 2); ?></td>
                    <td><?php echo $totalNilai/$count >= 90 ? 'A' : ($totalNilai/$count >= 80 ? 'A-' : ($totalNilai/$count >= 70 ? 'B+' : ($totalNilai/$count >= 60 ? 'B' : 'C'))); ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>