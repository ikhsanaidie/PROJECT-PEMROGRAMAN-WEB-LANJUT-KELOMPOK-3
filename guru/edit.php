<?php
// guru/edit.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';
/** @var mysqli $conn */

$nip = $_GET['id'] ?? '';
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_guru WHERE nip = '$nip'"));
if (!$data) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip_baru = $_POST['nip'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $jk = $_POST['jk'] ?? '';
    $mapel = $_POST['mapel'] ?? '';
    $status = $_POST['status'] ?? 'aktif';

    if (empty($nip_baru) || empty($nama) || empty($mapel)) {
        $error = 'Semua field harus diisi!';
    } else {
        $query = "UPDATE tbl_guru SET 
                  nip = '$nip_baru', 
                  nama_guru = '$nama', 
                  jk = '$jk', 
                  mapel = '$mapel',
                  status = '$status'
                  WHERE nip = '$nip'";
        if (mysqli_query($conn, $query)) {
            $success = 'Data berhasil diupdate!';
            $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_guru WHERE nip = '$nip_baru'"));
            $nip = $nip_baru;
        } else {
            $error = 'Gagal update: ' . mysqli_error($conn);
        }
    }
}
?>
<div class="page-header">
    <h2>Edit Guru</h2>
    <a href="index.php" class="btn btn-secondary">← Kembali</a>
</div>

<?php if ($error): ?>
<div class="notification notification-danger"><?php echo $error; ?></div>
<?php endif; ?>
<?php if ($success): ?>
<div class="notification notification-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card">
    <form method="POST">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
            <div class="form-group">
                <label>NIP *</label>
                <input type="text" name="nip" class="form-control" value="<?php echo $data['nip']; ?>" required>
            </div>
            <div class="form-group">
                <label>Nama Guru *</label>
                <input type="text" name="nama" class="form-control" value="<?php echo $data['nama_guru']; ?>" required>
            </div>
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jk" class="form-control">
                    <option value="Laki-laki" <?php echo $data['jk'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo $data['jk'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Mata Pelajaran *</label>
                <input type="text" name="mapel" class="form-control" value="<?php echo $data['mapel']; ?>" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="aktif" <?php echo ($data['status'] ?? 'aktif') == 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="nonaktif" <?php echo ($data['status'] ?? '') == 'nonaktif' ? 'selected' : ''; ?>>Nonaktif</option>
                </select>
            </div>
        </div>
        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-success">UPDATE</button>
            <a href="index.php" class="btn btn-secondary">BATAL</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>