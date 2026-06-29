<?php
// kelas/tambah.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

$error = '';
$success = '';

// Ambil data guru untuk dropdown wali kelas
$guruList = mysqli_query($conn, "SELECT nip, nama_guru FROM tbl_guru ORDER BY nama_guru");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kelas = $_POST['id_kelas'] ?? '';
    $nama_kelas = $_POST['nama_kelas'] ?? '';
    $wali_kelas = $_POST['wali_kelas'] ?? '';

    if (empty($id_kelas) || empty($nama_kelas)) {
        $error = 'ID Kelas dan Nama Kelas harus diisi!';
    } else {
        // Cek duplikat
        $cek = mysqli_query($conn, "SELECT * FROM tbl_kelas WHERE id_kelas = '$id_kelas'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'ID Kelas sudah ada!';
        } else {
            $query = "INSERT INTO tbl_kelas (id_kelas, nama_kelas, wali_kelas) VALUES ('$id_kelas', '$nama_kelas', '$wali_kelas')";
            if (mysqli_query($conn, $query)) {
                $success = 'Data berhasil ditambahkan!';
            } else {
                $error = 'Gagal menambahkan data: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<div class="page-header">
    <h2>Tambah Kelas</h2>
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
                <input type="text" name="id_kelas" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nama Kelas *</label>
                <input type="text" name="nama_kelas" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jurusan</label>
                <select name="jurusan" class="form-control">
                    <option value="MIPA">MIPA</option>
                    <option value="IPS">IPS</option>
                    <option value="Bahasa">Bahasa</option>
                </select>
            </div>
            <div class="form-group">
                <label>Wali Kelas</label>
                <select name="wali_kelas" class="form-control">
                    <option value="">-- Pilih Wali Kelas --</option>
                    <?php while($g = mysqli_fetch_assoc($guruList)): ?>
                    <option value="<?php echo $g['nip']; ?>"><?php echo $g['nama_guru']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-success">SIMPAN</button>
            <a href="index.php" class="btn btn-secondary">BATAL</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>