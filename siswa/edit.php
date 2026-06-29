<?php
// siswa/edit.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

$nisn = $_GET['id'] ?? '';
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_siswa WHERE nisn = '$nisn'"));
if (!$data) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn_baru = $_POST['nisn'] ?? '';
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

    if (empty($nisn_baru) || empty($nis) || empty($nama)) {
        $error = 'NISN, NIS, dan Nama harus diisi!';
    } else {
        $query = "UPDATE tbl_siswa SET 
                  nisn = '$nisn_baru', nis = '$nis', nama = '$nama', jk = '$jk',
                  tempat_lahir = '$tempat_lahir', tgl_lahir = '$tgl_lahir', agama = '$agama',
                  alamat = '$alamat', no_hp = '$no_hp', email = '$email', kelas = '$kelas',
                  jurusan = '$jurusan', tahun_masuk = '$tahun_masuk', nama_ayah = '$nama_ayah',
                  nama_ibu = '$nama_ibu'
                  WHERE nisn = '$nisn'";
        if (mysqli_query($conn, $query)) {
            $success = 'Data berhasil diupdate!';
            $data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_siswa WHERE nisn = '$nisn_baru'"));
            $nisn = $nisn_baru;
        } else {
            $error = 'Gagal update: ' . mysqli_error($conn);
        }
    }
}

$kelasList = mysqli_query($conn, "SELECT nama_kelas FROM tbl_kelas ORDER BY nama_kelas");
?>
<div class="page-header">
    <h2>Edit Siswa</h2>
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
                <input type="text" name="nisn" class="form-control" value="<?php echo $data['nisn']; ?>" required>
            </div>
            <div class="form-group">
                <label>NIS *</label>
                <input type="text" name="nis" class="form-control" value="<?php echo $data['nis']; ?>" required>
            </div>
            <div class="form-group">
                <label>Nama Lengkap *</label>
                <input type="text" name="nama" class="form-control" value="<?php echo $data['nama']; ?>" required>
            </div>
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jk" class="form-control">
                    <option value="Laki-laki" <?php echo $data['jk'] == 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="Perempuan" <?php echo $data['jk'] == 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control" value="<?php echo $data['tempat_lahir']; ?>">
            </div>
            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" class="form-control" value="<?php echo $data['tgl_lahir']; ?>">
            </div>
            <div class="form-group">
                <label>Agama</label>
                <select name="agama" class="form-control">
                    <?php
                    $agamaList = ['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'];
                    foreach ($agamaList as $a) {
                        $sel = $data['agama'] == $a ? 'selected' : '';
                        echo "<option value='$a' $sel>$a</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" class="form-control" value="<?php echo $data['alamat']; ?>">
            </div>
            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control" value="<?php echo $data['no_hp']; ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>">
            </div>
            <div class="form-group">
                <label>Kelas</label>
                <select name="kelas" class="form-control">
                    <option value="">Pilih Kelas</option>
                    <?php while($k = mysqli_fetch_assoc($kelasList)): ?>
                    <option value="<?php echo $k['nama_kelas']; ?>" <?php echo $data['kelas'] == $k['nama_kelas'] ? 'selected' : ''; ?>><?php echo $k['nama_kelas']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Jurusan</label>
                <select name="jurusan" class="form-control">
                    <option value="MIPA" <?php echo $data['jurusan'] == 'MIPA' ? 'selected' : ''; ?>>MIPA</option>
                    <option value="IPS" <?php echo $data['jurusan'] == 'IPS' ? 'selected' : ''; ?>>IPS</option>
                    <option value="Bahasa" <?php echo $data['jurusan'] == 'Bahasa' ? 'selected' : ''; ?>>Bahasa</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tahun Masuk</label>
                <input type="number" name="tahun_masuk" class="form-control" value="<?php echo $data['tahun_masuk']; ?>">
            </div>
            <div class="form-group">
                <label>Nama Ayah</label>
                <input type="text" name="nama_ayah" class="form-control" value="<?php echo $data['nama_ayah']; ?>">
            </div>
            <div class="form-group">
                <label>Nama Ibu</label>
                <input type="text" name="nama_ibu" class="form-control" value="<?php echo $data['nama_ibu']; ?>">
            </div>
        </div>
        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-success">UPDATE</button>
            <a href="index.php" class="btn btn-secondary">BATAL</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>