<?php
// pembayaran/index.php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';
/** @var mysqli $conn */

$nisn = $_GET['nisn'] ?? '';
$data_siswa = null;
if ($nisn) {
    $data_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_siswa WHERE nisn = '$nisn'"));
}

$error = '';
$success = '';

// Hitung tunggakan
$totalTunggakan = 0;
if ($nisn) {
    $tunggakan = mysqli_query($conn, "SELECT SUM(sisa_tagihan) as total FROM tbl_pembayaran WHERE nisn = '$nisn' AND status != 'Lunas'");
    $totalTunggakan = mysqli_fetch_assoc($tunggakan)['total'] ?? 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisn = $_POST['nisn'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $jenis = $_POST['jenis_tagihan'] ?? '';
    $bulan = $_POST['bulan'] ?? '';
    $total = $_POST['total_tagihan'] ?? 0;
    $dibayar = $_POST['dibayar'] ?? 0;
    $metode = $_POST['metode'] ?? 'Tunai';
    $sisa = $total - $dibayar;
    $status = $sisa <= 0 ? 'Lunas' : ($dibayar > 0 ? 'Cicilan' : 'Belum Bayar');
    $tgl = date('Y-m-d');

    if ($dibayar > $total) {
        $error = 'Jumlah dibayar tidak boleh melebihi total tagihan!';
    } else {
        // Cek apakah sudah ada
        $cek = mysqli_query($conn, "SELECT * FROM tbl_pembayaran WHERE nisn = '$nisn' AND jenis_tagihan = '$jenis'");
        if ($jenis == 'SPP') {
            $cek = mysqli_query($conn, "SELECT * FROM tbl_pembayaran WHERE nisn = '$nisn' AND jenis_tagihan = '$jenis' AND bulan = '$bulan'");
        }
        
        if (mysqli_num_rows($cek) > 0) {
            $row = mysqli_fetch_assoc($cek);
            $totalDibayarBaru = $row['dibayar'] + $dibayar;
            $sisaBaru = $row['total_tagihan'] - $totalDibayarBaru;
            $statusBaru = $sisaBaru <= 0 ? 'Lunas' : 'Cicilan';
            $query = "UPDATE tbl_pembayaran SET 
                      dibayar = '$totalDibayarBaru', 
                      sisa_tagihan = '$sisaBaru', 
                      status = '$statusBaru', 
                      tgl_bayar = '$tgl' 
                      WHERE id_pembayaran = '{$row['id_pembayaran']}'";
        } else {
            $query = "INSERT INTO tbl_pembayaran (nisn, nama_siswa, jenis_tagihan, bulan, total_tagihan, dibayar, sisa_tagihan, status, tgl_bayar, metode) 
                      VALUES ('$nisn', '$nama', '$jenis', '$bulan', '$total', '$dibayar', '$sisa', '$status', '$tgl', '$metode')";
        }
        
        if (mysqli_query($conn, $query)) {
            $success = 'Pembayaran berhasil!';
            // Redirect untuk refresh data
            header('Location: index.php?nisn=' . $nisn);
            exit;
        } else {
            $error = 'Gagal: ' . mysqli_error($conn);
        }
    }
}

$riwayat = mysqli_query($conn, "SELECT * FROM tbl_pembayaran ORDER BY tgl_bayar DESC");
?>
<div class="page-header">
    <h2>Pembayaran SPP</h2>
</div>

<?php if ($error): ?>
<div class="notification notification-danger"><?php echo $error; ?></div>
<?php endif; ?>
<?php if ($success): ?>
<div class="notification notification-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-title">Form Pembayaran</div>
    <form method="GET" style="margin-bottom:15px;">
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <label>NISN:</label>
            <input type="text" name="nisn" class="form-control" style="width:200px;" value="<?php echo $nisn; ?>">
            <button type="submit" class="btn btn-primary">CARI SISWA</button>
            <a href="index.php" class="btn btn-secondary">RESET</a>
        </div>
    </form>
    
    <?php if ($data_siswa): ?>
    <div style="background:#f5f5f5;padding:15px;border-radius:8px;margin-bottom:15px;">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
            <div><strong>Nama:</strong> <?php echo $data_siswa['nama']; ?></div>
            <div><strong>Kelas:</strong> <?php echo $data_siswa['kelas']; ?></div>
            <div><strong>Total Tunggakan:</strong> <span style="color:red;font-weight:bold;"><?php echo number_format($totalTunggakan, 0, ',', '.'); ?></span></div>
        </div>
    </div>
    
    <form method="POST">
        <input type="hidden" name="nisn" value="<?php echo $data_siswa['nisn']; ?>">
        <input type="hidden" name="nama" value="<?php echo $data_siswa['nama']; ?>">
        
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;">
            <div class="form-group">
                <label>Jenis Tagihan</label>
                <select name="jenis_tagihan" class="form-control" required onchange="setDefaultTagihan()">
                    <option value="SPP">SPP</option>
                    <option value="DAFTAR_ULANG">Daftar Ulang</option>
                    <option value="UANG_GEDUNG">Uang Gedung</option>
                </select>
            </div>
            <div class="form-group">
                <label>Bulan (untuk SPP)</label>
                <select name="bulan" class="form-control">
                    <option value="Januari">Januari</option>
                    <option value="Februari">Februari</option>
                    <option value="Maret">Maret</option>
                    <option value="April">April</option>
                    <option value="Mei">Mei</option>
                    <option value="Juni">Juni</option>
                    <option value="Juli">Juli</option>
                    <option value="Agustus">Agustus</option>
                    <option value="September">September</option>
                    <option value="Oktober">Oktober</option>
                    <option value="November">November</option>
                    <option value="Desember">Desember</option>
                </select>
            </div>
            <div class="form-group">
                <label>Metode Pembayaran</label>
                <select name="metode" class="form-control">
                    <option value="Tunai">Tunai</option>
                    <option value="Transfer">Transfer</option>
                </select>
            </div>
            <div class="form-group">
                <label>Total Tagihan</label>
                <input type="number" name="total_tagihan" id="total_tagihan" class="form-control" required 
                       onchange="hitungSisa()" onkeyup="hitungSisa()">
            </div>
            <div class="form-group">
                <label>Jumlah Dibayar</label>
                <input type="number" name="dibayar" id="dibayar" class="form-control" required 
                       onchange="hitungSisa()" onkeyup="hitungSisa()">
            </div>
            <div class="form-group">
                <label>Sisa Tagihan</label>
                <input type="text" id="sisa_tagihan" class="form-control" readonly style="font-weight:bold;background:#f0f0f0;">
            </div>
        </div>
        <div style="margin-top:15px;">
            <button type="submit" class="btn btn-success">💾 SIMPAN PEMBAYARAN</button>
            <a href="cetak.php?nisn=<?php echo $nisn; ?>" class="btn btn-primary" target="_blank">📄 CETAK NOTA</a>
        </div>
    </form>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-title">Riwayat Pembayaran</div>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th>Jenis</th>
                    <th>Bulan</th>
                    <th>Total</th>
                    <th>Dibayar</th>
                    <th>Sisa</th>
                    <th>Status</th>
                    <th>Metode</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($riwayat)): ?>
                <tr>
                    <td><?php echo $row['id_pembayaran']; ?></td>
                    <td><?php echo $row['nisn']; ?></td>
                    <td><?php echo $row['nama_siswa']; ?></td>
                    <td><?php echo $row['jenis_tagihan']; ?></td>
                    <td><?php echo $row['bulan'] ?? '-'; ?></td>
                    <td><?php echo number_format($row['total_tagihan'], 0, ',', '.'); ?></td>
                    <td><?php echo number_format($row['dibayar'], 0, ',', '.'); ?></td>
                    <td><?php echo number_format($row['sisa_tagihan'], 0, ',', '.'); ?></td>
                    <td>
                        <?php
                        $bg = $row['status'] == 'Lunas' ? 'green' : ($row['status'] == 'Cicilan' ? 'orange' : 'red');
                        echo "<span style='background:$bg;color:white;padding:2px 8px;border-radius:4px;font-size:11px;'>{$row['status']}</span>";
                        ?>
                    </td>
                    <td><?php echo $row['metode'] ?? '-'; ?></td>
                    <td><?php echo $row['tgl_bayar']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function setDefaultTagihan() {
    const jenis = document.querySelector('[name="jenis_tagihan"]').value;
    const total = document.getElementById('total_tagihan');
    if (jenis === 'SPP') total.value = 250000;
    else if (jenis === 'DAFTAR_ULANG') total.value = 300000;
    else if (jenis === 'UANG_GEDUNG') total.value = 1500000;
    hitungSisa();
}

function hitungSisa() {
    const total = parseFloat(document.getElementById('total_tagihan').value) || 0;
    const dibayar = parseFloat(document.getElementById('dibayar').value) || 0;
    const sisa = Math.max(0, total - dibayar);
    document.getElementById('sisa_tagihan').value = sisa.toLocaleString('id-ID');
}

document.addEventListener('DOMContentLoaded', function() {
    setDefaultTagihan();
});
</script>

<?php include '../includes/footer.php'; ?>