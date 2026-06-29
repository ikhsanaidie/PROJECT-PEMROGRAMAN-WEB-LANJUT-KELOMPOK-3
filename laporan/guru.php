<?php
// laporan/guru.php
session_start();
if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'kepsek')) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';
/** @var mysqli $conn */

$search = $_GET['search'] ?? '';

$query = "SELECT * FROM tbl_guru";
if ($search) $query .= " WHERE nama_guru LIKE '%$search%' OR nip LIKE '%$search%' OR mapel LIKE '%$search%'";
$query .= " ORDER BY nama_guru";

$result = mysqli_query($conn, $query);
$total = mysqli_num_rows($result);
?>
<div class="page-header">
    <h2>Laporan Data Guru</h2>
    <a href="guru_pdf.php?search=<?php echo urlencode($search); ?>" class="btn btn-primary" target="_blank">📄 Cetak PDF</a>
</div>

<div class="filter-bar">
    <label>Cari:</label>
    <input type="text" id="searchInput" class="form-control" placeholder="NIP/Nama/Mapel" style="width:250px;" value="<?php echo $search; ?>">
    <button class="btn btn-primary" onclick="cariData()">CARI</button>
    <button class="btn btn-secondary" onclick="resetFilter()">RESET</button>
</div>

<div class="notification notification-info">
    Total data: <strong><?php echo $total; ?></strong> guru
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama Guru</th>
                <th>JK</th>
                <th>Mata Pelajaran</th>
                <th>Status</th>
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
            </tr>
            <?php endwhile; ?>
            <?php if ($total == 0): ?>
            <tr><td colspan="6" style="text-align:center;padding:30px;">Tidak ada data guru</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function cariData() {
    const search = document.getElementById('searchInput').value;
    location.href = 'guru.php?search=' + encodeURIComponent(search);
}

function resetFilter() {
    document.getElementById('searchInput').value = '';
    location.href = 'guru.php';
}

document.getElementById('searchInput').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') cariData();
});

</script>

<?php include '../includes/footer.php'; ?>