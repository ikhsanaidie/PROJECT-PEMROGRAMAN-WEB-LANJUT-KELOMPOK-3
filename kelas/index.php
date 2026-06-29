<?php
// kelas/index.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

$result = mysqli_query($conn, "SELECT k.*, g.nama_guru FROM tbl_kelas k LEFT JOIN tbl_guru g ON k.wali_kelas = g.nip ORDER BY k.nama_kelas");
?>
<div class="page-header">
    <h2>Data Kelas</h2>
    <a href="tambah.php" class="btn btn-success">+ Tambah Kelas</a>
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Kelas</th>
                <th>Nama Kelas</th>
                <th>Wali Kelas</th>
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
                <td><?php echo $row['id_kelas']; ?></td>
                <td><?php echo $row['nama_kelas']; ?></td>
                <td><?php echo $row['nama_guru'] ?? '-'; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['id_kelas']; ?>" class="btn btn-primary btn-sm">✏️</a>
                    <a href="hapus.php?id=<?php echo $row['id_kelas']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">🗑️</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>