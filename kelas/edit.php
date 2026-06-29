<?php
// kelas/edit.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

$id_kelas = $_GET['id'] ?? '';
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_kelas WHERE id_kelas = '$id_kelas'"));
if (!$data) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';
$guruList = mysqli_query($conn, "SELECT nip, nama_guru FROM tbl_guru ORDER BY nama_guru");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_baru = $_POST['id_kelas'] ?? '';
    $nama = $_POST['nama_kelas'] ?? '';
    $wali = $_POST['wali_kelas'] ?? '';

    if (empty($id_baru) || empty($nama)) {
        $error = 'ID Kelas dan Nama Kelas harus diisi!';
    } else {
        $query = "UPDATE tbl_kelas SET 
                  id_kelas = '$id_baru', 
                  nama_kelas = '$nama', 
                  wali_kelas = '$wali' 
                  WHERE id_kelas = '$id_kelas'";
        if (mysqli_query($conn, $query)) {
            $success = 'Data berhasil diupdate!';
            $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_kelas WHERE id_kelas = '$id_baru'"));
            $id_kelas = $id_baru;
        } else {
            $error = 'Gagal update: ' . mysqli_error($conn);
        }
    }
}
?>
<div class="page-header">
    <h2>Edit Kelas</h2>
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
                <label>ID Kelas *</label>
                <input type="text" name="id_kelas" class="form-control" value="<?php echo $data['id_kelas']; ?>" required>
            </div>
            <div class="form-group">
                <label>Nama Kelas *</label>
                <input type="text" name="nama_kelas" class="form-control" value="<?php echo $data['nama_kelas']; ?>" required>
            </div>
            <div class="form-group">
                <label>Wali Kelas</label>
                <select name="wali_kelas" class="form-control">
                    <option value="">-- Pilih Wali Kelas --</option>
                    <?php while($g = mysqli_fetch_assoc($guruList)): ?>
                    <option value="<?php echo $g['nip']; ?>" <?php echo $data['wali_kelas'] == $g['nip'] ? 'selected' : ''; ?>><?php echo $g['nama_guru']; ?></option>
                    <?php endwhile; ?>
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