<?php
// laporan/nilai.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

$kelas = $_GET['kelas'] ?? '';
$mapel = $_GET['mapel'] ?? '';
$search = $_GET['search'] ?? '';

// Query nilai dengan join ke siswa untuk mendapatkan kelas
$query = "SELECT n.*, s.kelas FROM tbl_nilai n LEFT JOIN tbl_siswa s ON n.nisn = s.nisn WHERE 1=1";
if ($kelas) $query .= " AND s.kelas = '$kelas'";
if ($mapel) $query .= " AND n.mata_pelajaran = '$mapel'";
if ($search) $query .= " AND (n.nisn LIKE '%$search%' OR n.nama_siswa LIKE '%$search%')";
$query .= " ORDER BY n.nisn";

$result = mysqli_query($conn, $query);
$total = mysqli_num_rows($result);

// Ambil daftar kelas untuk filter
$kelasList = mysqli_query($conn, "SELECT nama_kelas FROM tbl_kelas ORDER BY nama_kelas");

// Ambil daftar mata pelajaran dari data nilai
$mapelList = mysqli_query($conn, "SELECT DISTINCT mata_pelajaran FROM tbl_nilai ORDER BY mata_pelajaran");
?>
<div class="page-header">
    <h2>Laporan Nilai Siswa</h2>
    <a href="nilai_pdf.php?kelas=<?php echo $kelas; ?>&mapel=<?php echo urlencode($mapel); ?>&search=<?php echo urlencode($search); ?>" class="btn btn-primary" target="_blank">📄 Cetak PDF</a>
</div>

<div class="filter-bar">
    <label>Kelas:</label>
    <select class="form-control" style="width:150px;" onchange="filterData()" id="filterKelas">
        <option value="">-- Semua --</option>
        <?php while($k = mysqli_fetch_assoc($kelasList)): ?>
        <option value="<?php echo $k['nama_kelas']; ?>" <?php echo $kelas == $k['nama_kelas'] ? 'selected' : ''; ?>><?php echo $k['nama_kelas']; ?></option>
        <?php endwhile; ?>
    </select>
    
    <label>Mapel:</label>
    <select class="form-control" style="width:150px;" onchange="filterData()" id="filterMapel">
        <option value="">-- Semua --</option>
        <?php while($m = mysqli_fetch_assoc($mapelList)): ?>
        <option value="<?php echo $m['mata_pelajaran']; ?>" <?php echo $mapel == $m['mata_pelajaran'] ? 'selected' : ''; ?>><?php echo $m['mata_pelajaran']; ?></option>
        <?php endwhile; ?>
    </select>
    
    <label>Cari:</label>
    <input type="text" id="searchInput" class="form-control" placeholder="NISN/Nama" style="width:180px;" value="<?php echo $search; ?>">
    <button class="btn btn-primary" onclick="filterData()">TAMPILKAN</button>
    <button class="btn btn-secondary" onclick="resetFilter()">RESET</button>
</div>

<div class="notification notification-info">
    Total data: <strong><?php echo $total; ?></strong> nilai
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Guru</th>
                <th>Tugas</th>
                <th>UTS</th>
                <th>UAS</th>
                <th>Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)):
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['nisn']; ?></td>
                <td><?php echo $row['nama_siswa']; ?></td>
                <td><?php echo $row['kelas'] ?? '-'; ?></td>
                <td><?php echo $row['mata_pelajaran']; ?></td>
                <td><?php echo $row['guru_pengajar']; ?></td>
                <td><?php echo $row['tugas']; ?></td>
                <td><?php echo $row['uts']; ?></td>
                <td><?php echo $row['uas']; ?></td>
                <td><strong><?php echo number_format($row['nilai_akhir'], 2); ?></strong></td>
            </tr>
            <?php endwhile; ?>
            <?php if ($total == 0): ?>
            <tr><td colspan="10" style="text-align:center;padding:30px;">Tidak ada data nilai</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function filterData() {
    const kelas = document.getElementById('filterKelas').value;
    const mapel = document.getElementById('filterMapel').value;
    const search = document.getElementById('searchInput').value;
    location.href = 'nilai.php?kelas=' + kelas + '&mapel=' + encodeURIComponent(mapel) + '&search=' + encodeURIComponent(search);
}

function resetFilter() {
    document.getElementById('filterKelas').value = '';
    document.getElementById('filterMapel').value = '';
    document.getElementById('searchInput').value = '';
    location.href = 'nilai.php';
}

document.getElementById('searchInput').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') filterData();
});
</script>

<?php include '../includes/footer.php'; ?>