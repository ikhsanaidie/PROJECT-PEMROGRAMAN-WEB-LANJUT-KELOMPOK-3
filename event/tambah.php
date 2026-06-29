<?php
// event/tambah.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

$error = '';
$success = '';
$kelasList = mysqli_query($conn, "SELECT nama_kelas FROM tbl_kelas ORDER BY nama_kelas");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $tgl_mulai = $_POST['tgl_mulai'] ?? '';
    $tgl_selesai = $_POST['tgl_selesai'] ?? '';
    $tipe = $_POST['tipe_event'] ?? '';
    $kelas_target = $_POST['kelas_target'] ?? 'Semua Kelas';
    $dibuat_oleh = $_SESSION['nama'] ?? 'System';

    if (empty($judul) || empty($tgl_mulai)) {
        $error = 'Judul dan Tanggal Mulai harus diisi!';
    } else {
        $query = "INSERT INTO tbl_event (judul, deskripsi, tgl_mulai, tgl_selesai, tipe_event, kelas_target, dibuat_oleh) 
                  VALUES ('$judul', '$deskripsi', '$tgl_mulai', '$tgl_selesai', '$tipe', '$kelas_target', '$dibuat_oleh')";
        if (mysqli_query($conn, $query)) {
            $success = 'Event berhasil ditambahkan!';
        } else {
            $error = 'Gagal: ' . mysqli_error($conn);
        }
    }
}
?>
<div class="page-header">
    <h2>Tambah Event</h2>
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
                <label>Judul Event *</label>
                <input type="text" name="judul" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Tipe Event</label>
                <select name="tipe_event" class="form-control">
                    <option value="Akademik">Akademik</option>
                    <option value="Ujian">Ujian</option>
                    <option value="Libur">Libur</option>
                    <option value="Keuangan">Keuangan</option>
                    <option value="Ekstrakurikuler">Ekstrakurikuler</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Mulai *</label>
                <input type="date" name="tgl_mulai" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" class="form-control">
            </div>
            <div class="form-group">
                <label>Target Kelas</label>
                <select name="kelas_target" class="form-control">
                    <option value="Semua Kelas">Semua Kelas</option>
                    <?php while($k = mysqli_fetch_assoc($kelasList)): ?>
                    <option value="<?php echo $k['nama_kelas']; ?>"><?php echo $k['nama_kelas']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-success">SIMPAN</button>
            <a href="index.php" class="btn btn-secondary">BATAL</a>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>