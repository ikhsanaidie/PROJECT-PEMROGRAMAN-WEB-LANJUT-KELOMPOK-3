<?php
// laporan/siswa.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

$kelas = $_GET['kelas'] ?? '';
$search = $_GET['search'] ?? '';

$query = "SELECT * FROM tbl_siswa";
$conditions = [];
if ($kelas) $conditions[] = "kelas = '$kelas'";
if ($search) $conditions[] = "(nama LIKE '%$search%' OR nisn LIKE '%$search%')";
if ($conditions) $query .= " WHERE " . implode(" AND ", $conditions);
$query .= " ORDER BY nama";

$result = mysqli_query($conn, $query);
$total = mysqli_num_rows($result);

// Ambil daftar kelas untuk filter
$kelasList = mysqli_query($conn, "SELECT nama_kelas FROM tbl_kelas ORDER BY nama_kelas");
?>
<div class="page-header">
    <h2>Laporan Data Siswa</h2>
    <div>
        <a href="siswa_pdf.php?kelas=<?php echo $kelas; ?>&search=<?php echo urlencode($search); ?>" class="btn btn-primary" target="_blank">📄 Cetak PDF (Semua)</a>
        <?php if ($kelas): ?>
        <a href="siswa_pdf.php?kelas=<?php echo $kelas; ?>&search=<?php echo urlencode($search); ?>" class="btn btn-success" target="_blank">📄 Cetak PDF (Per Kelas)</a>
        <?php endif; ?>
    </div>
</div>

<div class="filter-bar">
    <label>Kelas:</label>
    <select class="form-control" style="width:180px;" onchange="location.href='siswa.php?kelas='+this.value+'&search=<?php echo urlencode($search); ?>'">
        <option value="">-- Semua Kelas --</option>
        <?php while($k = mysqli_fetch_assoc($kelasList)): ?>
        <option value="<?php echo $k['nama_kelas']; ?>" <?php echo $kelas == $k['nama_kelas'] ? 'selected' : ''; ?>><?php echo $k['nama_kelas']; ?></option>
        <?php endwhile; ?>
    </select>
    
    <label>Cari:</label>
    <input type="text" id="searchInput" class="form-control" placeholder="NISN/Nama" style="width:200px;" value="<?php echo $search; ?>">
    <button class="btn btn-primary" onclick="cariData()">CARI</button>
    <button class="btn btn-secondary" onclick="resetFilter()">RESET</button>
</div>

<div class="notification notification-info">
    Total data: <strong><?php echo $total; ?></strong> siswa
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>JK</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Agama</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>Email</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Tahun Masuk</th>
                <th>Ayah</th>
                <th>Ibu</th>
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
                <td><?php echo $row['nis']; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['jk']; ?></td>
                <td><?php echo $row['tempat_lahir']; ?></td>
                <td><?php echo $row['tgl_lahir']; ?></td>
                <td><?php echo $row['agama']; ?></td>
                <td><?php echo substr($row['alamat'], 0, 30) . (strlen($row['alamat']) > 30 ? '...' : ''); ?></td>
                <td><?php echo $row['no_hp']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['kelas']; ?></td>
                <td><?php echo $row['jurusan']; ?></td>
                <td><?php echo $row['tahun_masuk']; ?></td>
                <td><?php echo $row['nama_ayah']; ?></td>
                <td><?php echo $row['nama_ibu']; ?></td>
            </tr>
            <?php endwhile; ?>
            <?php if ($total == 0): ?>
            <tr><td colspan="16" style="text-align:center;padding:30px;">Tidak ada data siswa</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function cariData() {
    const search = document.getElementById('searchInput').value;
    const kelas = document.querySelector('select').value;
    location.href = 'siswa.php?kelas=' + kelas + '&search=' + encodeURIComponent(search);
}

function resetFilter() {
    document.getElementById('searchInput').value = '';
    location.href = 'siswa.php';
}

document.getElementById('searchInput').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') cariData();
});
</script>

<?php include '../includes/footer.php'; ?>