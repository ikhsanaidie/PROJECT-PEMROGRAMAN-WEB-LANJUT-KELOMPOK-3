<?php
// nilai/index.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

$nisn = $_GET['nisn'] ?? '';
$data_siswa = null;
if ($nisn) {
    $data_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_siswa WHERE nisn = '$nisn'"));
}

// Ambil daftar guru untuk dropdown mapel
$guruList = mysqli_query($conn, "SELECT nama_guru, mapel FROM tbl_guru ORDER BY mapel");

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = $_POST['nisn'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $mata_pelajaran = $_POST['mata_pelajaran'] ?? '';
    $guru_pengajar = $_POST['guru_pengajar'] ?? '';
    $tugas = $_POST['tugas'] ?? 0;
    $uts = $_POST['uts'] ?? 0;
    $uas = $_POST['uas'] ?? 0;
    $nilai_akhir = ($tugas * 0.2) + ($uts * 0.3) + ($uas * 0.5);

    if (empty($nisn) || empty($mata_pelajaran)) {
        $error = 'NISN dan Mata Pelajaran harus diisi!';
    } else {
        // Cek apakah sudah ada
        $cek = mysqli_query($conn, "SELECT * FROM tbl_nilai WHERE nisn = '$nisn' AND mata_pelajaran = '$mata_pelajaran'");
        if (mysqli_num_rows($cek) > 0) {
            $query = "UPDATE tbl_nilai SET 
                      guru_pengajar = '$guru_pengajar', 
                      tugas = '$tugas', 
                      uts = '$uts', 
                      uas = '$uas', 
                      nilai_akhir = '$nilai_akhir' 
                      WHERE nisn = '$nisn' AND mata_pelajaran = '$mata_pelajaran'";
        } else {
            $query = "INSERT INTO tbl_nilai (nisn, nama_siswa, mata_pelajaran, guru_pengajar, tugas, uts, uas, nilai_akhir) 
                      VALUES ('$nisn', '$nama', '$mata_pelajaran', '$guru_pengajar', '$tugas', '$uts', '$uas', '$nilai_akhir')";
        }
        if (mysqli_query($conn, $query)) {
            $success = 'Nilai berhasil disimpan!';
        } else {
            $error = 'Gagal: ' . mysqli_error($conn);
        }
    }
}

// Ambil riwayat nilai
$nilaiList = mysqli_query($conn, "SELECT * FROM tbl_nilai ORDER BY nisn");
?>
<div class="page-header">
    <h2>Input Nilai</h2>
</div>

<?php if ($error): ?>
<div class="notification notification-danger"><?php echo $error; ?></div>
<?php endif; ?>
<?php if ($success): ?>
<div class="notification notification-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-title">Form Input Nilai</div>
    <form method="POST">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;">
            <div class="form-group">
                <label>NISN</label>
                <input type="text" name="nisn" id="nisn" class="form-control" value="<?php echo $nisn; ?>" required 
                       onchange="cariSiswa()">
                <button type="button" class="btn btn-primary btn-sm" style="margin-top:5px;" onclick="cariSiswa()">CARI SISWA</button>
            </div>
            <div class="form-group">
                <label>Nama Siswa</label>
                <input type="text" name="nama" id="nama" class="form-control" value="<?php echo $data_siswa['nama'] ?? ''; ?>" readonly>
            </div>
            <div class="form-group">
                <label>Kelas</label>
                <input type="text" id="kelas" class="form-control" value="<?php echo $data_siswa['kelas'] ?? '-'; ?>" readonly>
            </div>
            <div class="form-group">
                <label>Mata Pelajaran</label>
                <select name="mata_pelajaran" class="form-control" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    <?php while($g = mysqli_fetch_assoc($guruList)): ?>
                    <option value="<?php echo $g['mapel']; ?>"><?php echo $g['mapel']; ?> (<?php echo $g['nama_guru']; ?>)</option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Guru Pengajar</label>
                <input type="text" name="guru_pengajar" id="guru_pengajar" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Nilai Tugas (20%)</label>
                <input type="number" name="tugas" class="form-control" min="0" max="100" step="0.01" onchange="hitungNilai()">
            </div>
            <div class="form-group">
                <label>Nilai UTS (30%)</label>
                <input type="number" name="uts" class="form-control" min="0" max="100" step="0.01" onchange="hitungNilai()">
            </div>
            <div class="form-group">
                <label>Nilai UAS (50%)</label>
                <input type="number" name="uas" class="form-control" min="0" max="100" step="0.01" onchange="hitungNilai()">
            </div>
            <div class="form-group">
                <label>Nilai Akhir</label>
                <input type="text" id="nilai_akhir" class="form-control" readonly style="font-weight:bold;background:#f0f0f0;">
            </div>
        </div>
        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-success">SIMPAN NILAI</button>
            <button type="reset" class="btn btn-secondary">RESET</button>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-title">Riwayat Nilai</div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Tugas</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Nilai Akhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($nilaiList)): ?>
                <tr>
                    <td><?php echo $row['nisn']; ?></td>
                    <td><?php echo $row['nama_siswa']; ?></td>
                    <td><?php echo $row['mata_pelajaran']; ?></td>
                    <td><?php echo $row['guru_pengajar']; ?></td>
                    <td><?php echo $row['tugas']; ?></td>
                    <td><?php echo $row['uts']; ?></td>
                    <td><?php echo $row['uas']; ?></td>
                    <td><strong><?php echo number_format($row['nilai_akhir'], 2); ?></strong></td>
                    <td>
                        <a href="hapus.php?nisn=<?php echo $row['nisn']; ?>&mapel=<?php echo urlencode($row['mata_pelajaran']); ?>" 
                           class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">🗑️</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function cariSiswa() {
    const nisn = document.getElementById('nisn').value;
    if (nisn) {
        fetch('cari_siswa.php?nisn=' + nisn)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('nama').value = data.nama;
                    document.getElementById('kelas').value = data.kelas;
                } else {
                    alert('Siswa tidak ditemukan!');
                    document.getElementById('nama').value = '';
                    document.getElementById('kelas').value = '';
                }
            });
    }
}

function hitungNilai() {
    const tugas = parseFloat(document.querySelector('[name="tugas"]').value) || 0;
    const uts = parseFloat(document.querySelector('[name="uts"]').value) || 0;
    const uas = parseFloat(document.querySelector('[name="uas"]').value) || 0;
    const akhir = (tugas * 0.2) + (uts * 0.3) + (uas * 0.5);
    document.getElementById('nilai_akhir').value = akhir.toFixed(2);
}
</script>

<?php include '../includes/footer.php'; ?>