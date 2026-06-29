<?php
// guru/tambah.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = $_POST['nip'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $jk = $_POST['jk'] ?? '';
    $mapel = $_POST['mapel'] ?? '';

    if (empty($nip) || empty($nama) || empty($mapel)) {
        $error = 'Semua field harus diisi!';
    } else {
        // Cek duplikat NIP
        $cek = mysqli_query($conn, "SELECT * FROM tbl_guru WHERE nip = '$nip'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'NIP sudah ada!';
        } else {
            $query = "INSERT INTO tbl_guru (nip, jk, nama_guru, mapel, password, status) 
                      VALUES ('$nip', '$jk', '$nama', '$mapel', MD5('guru123'), 'aktif')";
            if (mysqli_query($conn, $query)) {
                $success = 'Data berhasil ditambahkan!<br>Password default: <strong>guru123</strong>';
            } else {
                $error = 'Gagal menambahkan data: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<div class="page-header">
    <h2>Tambah Guru</h2>
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
                <input type="text" name="nip" class="form-control" placeholder="Masukkan NIP" required>
            </div>
            <div class="form-group">
                <label>Nama Guru *</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jk" class="form-control">
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Mata Pelajaran *</label>
                <input type="text" name="mapel" class="form-control" placeholder="Contoh: Matematika" required>
            </div>
        </div>
        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-success">SIMPAN</button>
            <a href="index.php" class="btn btn-secondary">BATAL</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>