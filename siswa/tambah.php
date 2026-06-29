<?php
// siswa/tambah.php
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
    $nisn = $_POST['nisn'] ?? '';
    $nis = $_POST['nis'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $jk = $_POST['jk'] ?? '';
    $tempat_lahir = $_POST['tempat_lahir'] ?? '';
    $tgl_lahir = $_POST['tgl_lahir'] ?? '';
    $agama = $_POST['agama'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $no_hp = $_POST['no_hp'] ?? '';
    $email = $_POST['email'] ?? '';
    $kelas = $_POST['kelas'] ?? '';
    $jurusan = $_POST['jurusan'] ?? '';
    $tahun_masuk = $_POST['tahun_masuk'] ?? '';
    $nama_ayah = $_POST['nama_ayah'] ?? '';
    $nama_ibu = $_POST['nama_ibu'] ?? '';

    if (empty($nisn) || empty($nis) || empty($nama)) {
        $error = 'NISN, NIS, dan Nama harus diisi!';
    } else {
        // Cek duplikat
        $cek = mysqli_query($conn, "SELECT * FROM tbl_siswa WHERE nisn = '$nisn'");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'NISN sudah ada!';
        } else {
            $query = "INSERT INTO tbl_siswa (nisn, nis, nama, jk, tempat_lahir, tgl_lahir, agama, alamat, no_hp, email, kelas, jurusan, tahun_masuk, nama_ayah, nama_ibu) 
                      VALUES ('$nisn', '$nis', '$nama', '$jk', '$tempat_lahir', '$tgl_lahir', '$agama', '$alamat', '$no_hp', '$email', '$kelas', '$jurusan', '$tahun_masuk', '$nama_ayah', '$nama_ibu')";
            if (mysqli_query($conn, $query)) {
                $success = 'Data berhasil ditambahkan!';
            } else {
                $error = 'Gagal menambahkan data: ' . mysqli_error($conn);
            }
        }
    }
}

// Ambil data kelas untuk dropdown
$kelasList = mysqli_query($conn, "SELECT nama_kelas FROM tbl_kelas ORDER BY nama_kelas");
?>
<div class="page-header">
    <h2>Tambah Siswa</h2>
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
                <label>NISN *</label>
                <input type="text" name="nisn" class="form-control" required>
            </div>
            <div class="form-group">
                <label>NIS *</label>
                <input type="text" name="nis" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nama Lengkap *</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jk" class="form-control">
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control">
            </div>
            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" class="form-control">
            </div>
            <div class="form-group">
                <label>Agama</label>
                <select name="agama" class="form-control">
                    <option value="Islam">Islam</option>
                    <option value="Kristen">Kristen</option>
                    <option value="Katolik">Katolik</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Buddha">Buddha</option>
                    <option value="Konghucu">Konghucu</option>
                </select>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control">
            </div>
            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <label>Kelas</label>
                <select name="kelas" class="form-control">
                    <option value="">Pilih Kelas</option>
                    <?php while($k = mysqli_fetch_assoc($kelasList)): ?>
                    <option value="<?php echo $k['nama_kelas']; ?>"><?php echo $k['nama_kelas']; ?></option>
                    <?php endwhile; ?>
                </select>
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
                <label>Tahun Masuk</label>
                <input type="number" name="tahun_masuk" class="form-control" value="<?php echo date('Y'); ?>">
            </div>
            <div class="form-group">
                <label>Nama Ayah</label>
                <input type="text" name="nama_ayah" class="form-control">
            </div>
            <div class="form-group">
                <label>Nama Ibu</label>
                <input type="text" name="nama_ibu" class="form-control">
            </div>
        </div>
        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-success">SIMPAN</button>
            <a href="index.php" class="btn btn-secondary">BATAL</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>