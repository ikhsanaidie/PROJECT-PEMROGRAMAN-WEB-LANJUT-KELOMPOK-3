<?php
// siswa/index.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

// Ambil data siswa
$query = "SELECT * FROM tbl_siswa ORDER BY nama";
$result = mysqli_query($conn, $query);
?>
<div class="page-header">
    <h2>Data Siswa</h2>
    <a href="tambah.php" class="btn btn-success">+ Tambah Siswa</a>
</div>

<div class="filter-bar">
    <label>Filter Kelas:</label>
    <select id="filterKelas" class="form-control" style="width:180px;">
        <option value="">-- Semua Kelas --</option>
        <?php
        $kelas = mysqli_query($conn, "SELECT nama_kelas FROM tbl_kelas ORDER BY nama_kelas");
        while ($k = mysqli_fetch_assoc($kelas)) {
            echo "<option value='".$k['nama_kelas']."'>".$k['nama_kelas']."</option>";
        }
        ?>
    </select>
    <label>Cari:</label>
    <input type="text" id="searchInput" class="form-control" placeholder="Cari NISN/Nama" style="width:200px;">
    <button class="btn btn-primary" onclick="filterData()">CARI</button>
    <button class="btn btn-secondary" onclick="resetFilter()">RESET</button>
</div>

<div class="table-container">
    <table class="table" id="siswaTable">
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>JK</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Aksi</th>
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
                <td><?php echo $row['kelas']; ?></td>
                <td><?php echo $row['jurusan']; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['nisn']; ?>" class="btn btn-primary btn-sm">✏️</a>
                    <a href="hapus.php?id=<?php echo $row['nisn']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">🗑️</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
function filterData() {
    const kelas = document.getElementById('filterKelas').value;
    const search = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#siswaTable tbody tr');
    rows.forEach(row => {
        const rowKelas = row.cells[5].textContent;
        const rowNama = row.cells[3].textContent.toLowerCase();
        const rowNisn = row.cells[1].textContent.toLowerCase();
        const matchKelas = kelas === '' || rowKelas === kelas;
        const matchSearch = search === '' || rowNama.includes(search) || rowNisn.includes(search);
        row.style.display = matchKelas && matchSearch ? '' : 'none';
    });
}

function resetFilter() {
    document.getElementById('filterKelas').value = '';
    document.getElementById('searchInput').value = '';
    filterData();
}

document.getElementById('filterKelas').addEventListener('change', filterData);
document.getElementById('searchInput').addEventListener('keyup', filterData);
</script>

<?php include '../includes/footer.php'; ?>