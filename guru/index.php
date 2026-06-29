<?php
// guru/index.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

// Ambil data guru
$result = mysqli_query($conn, "SELECT * FROM tbl_guru ORDER BY nama_guru");
?>
<div class="page-header">
    <h2>Data Guru</h2>
    <a href="tambah.php" class="btn btn-success">+ Tambah Guru</a>
</div>

<div class="filter-bar">
    <label>Cari:</label>
    <input type="text" id="searchInput" class="form-control" placeholder="Cari NIP/Nama Guru" style="width:250px;">
    <button class="btn btn-primary" onclick="filterData()">CARI</button>
    <button class="btn btn-secondary" onclick="resetFilter()">RESET</button>
</div>

<div class="table-container">
    <table class="table" id="guruTable">
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama Guru</th>
                <th>JK</th>
                <th>Mata Pelajaran</th>
                <th>Status</th>
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
                <td><?php echo $row['nip']; ?></td>
                <td><?php echo $row['nama_guru']; ?></td>
                <td><?php echo $row['jk']; ?></td>
                <td><?php echo $row['mapel']; ?></td>
                <td>
                    <?php
                    $status = $row['status'] ?? 'aktif';
                    $bg = $status == 'aktif' ? 'green' : 'red';
                    echo "<span style='background:$bg;color:white;padding:2px 8px;border-radius:4px;font-size:11px;'>".ucfirst($status)."</span>";
                    ?>
                </td>
                <td>
                    <a href="edit.php?id=<?php echo $row['nip']; ?>" class="btn btn-primary btn-sm">✏️</a>
                    <a href="hapus.php?id=<?php echo $row['nip']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data guru ini?')">🗑️</a>
                    <a href="reset_password.php?id=<?php echo $row['nip']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Reset password ke default (guru123)?')">🔑</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
function filterData() {
    const search = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#guruTable tbody tr');
    rows.forEach(row => {
        const nip = row.cells[1].textContent.toLowerCase();
        const nama = row.cells[2].textContent.toLowerCase();
        const match = search === '' || nip.includes(search) || nama.includes(search);
        row.style.display = match ? '' : 'none';
    });
}

function resetFilter() {
    document.getElementById('searchInput').value = '';
    filterData();
}

document.getElementById('searchInput').addEventListener('keyup', filterData);
</script>

<?php include '../includes/footer.php'; ?>