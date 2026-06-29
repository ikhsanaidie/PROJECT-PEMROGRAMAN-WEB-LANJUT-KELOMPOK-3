<?php
// event/index.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';
/** @var mysqli $conn */

$events = mysqli_query($conn, "SELECT * FROM tbl_event ORDER BY tgl_mulai DESC");
?>
<div class="page-header">
    <h2>Kalender Akademik</h2>
    <a href="tambah.php" class="btn btn-success">+ Tambah Event</a>
</div>

<div class="card">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Judul</th>
                    <th>Tipe</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Target Kelas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($events)): ?>
                <tr>
                    <td><?php echo $row['id_event']; ?></td>
                    <td><?php echo $row['judul']; ?></td>
                    <td><?php echo $row['tipe_event']; ?></td>
                    <td><?php echo $row['tgl_mulai']; ?></td>
                    <td><?php echo $row['tgl_selesai'] ?? '-'; ?></td>
                    <td><?php echo $row['kelas_target'] ?? 'Semua Kelas'; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $row['id_event']; ?>" class="btn btn-primary btn-sm">✏️</a>
                        <a href="hapus.php?id=<?php echo $row['id_event']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">🗑️</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>