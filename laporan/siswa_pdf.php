<?php
// laporan/siswa_pdf.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
/** @var mysqli $conn */

// Set header untuk PDF
// header('Content-Type: application/pdf');
// header('Content-Disposition: inline; filename="Laporan_Siswa.pdf"');

$kelas = $_GET['kelas'] ?? '';
$search = $_GET['search'] ?? '';

$query = "SELECT * FROM tbl_siswa";
$conditions = [];
if ($kelas) $conditions[] = "kelas = '$kelas'";
if ($search) $conditions[] = "(nama LIKE '%$search%' OR nisn LIKE '%$search%')";
if ($conditions) $query .= " WHERE " . implode(" AND ", $conditions);
$query .= " ORDER BY nama";

$result = mysqli_query($conn, $query);

// Jika tidak ada data, redirect
if (mysqli_num_rows($result) == 0) {
    header('Location: siswa.php');
    exit;
}

// Gunakan HTML untuk PDF sederhana (bisa di-print)
?>
<!DOCTYPE html>
<html>

<head>
    <title>Laporan Siswa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            padding: 20px;
        }

        .kop {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop h1 {
            font-size: 18px;
            letter-spacing: 2px;
        }

        .kop p {
            font-size: 11px;
            color: #333;
        }

        .kop h2 {
            font-size: 14px;
            margin-top: 5px;
        }

        .info {
            font-size: 11px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        th {
            background: #1976D2;
            color: white;
            padding: 5px 4px;
            border: 1px solid #000;
            text-align: center;
        }

        td {
            padding: 4px;
            border: 1px solid #000;
        }

        .ttd {
            margin-top: 30px;
            text-align: right;
        }

        .ttd p {
            margin: 2px 0;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="margin-bottom:15px;">
        <button onclick="window.print()">🖨️ Cetak PDF</button>
        <button onclick="window.close()">✖ Tutup</button>
    </div>

    <div class="kop">
        <h1>SMA PGRI 4 JAKARTA</h1>
        <p>Jl. Cipayung Raya, Jakarta Timur 13840</p>
        <h2>LAPORAN DATA SISWA</h2>
    </div>

    <div class="info">
        <?php if ($kelas): ?>
            <strong>Kelas:</strong> <?php echo $kelas; ?> &nbsp;|&nbsp;
        <?php endif; ?>
        <?php if ($search): ?>
            <strong>Pencarian:</strong> <?php echo $search; ?> &nbsp;|&nbsp;
        <?php endif; ?>
        <strong>Total:</strong> <?php echo mysqli_num_rows($result); ?> siswa &nbsp;|&nbsp;
        <strong>Dicetak:</strong> <?php echo date('d F Y H:i:s'); ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>NIS</th>
                <th>Nama</th>
                <th>JK</th>
                <th>Tempat Lahir</th>
                <th>Tgl Lahir</th>
                <th>Agama</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Ayah</th>
                <th>Ibu</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)):
            ?>
                <tr>
                    <td style="text-align:center;"><?php echo $no++; ?></td>
                    <td><?php echo $row['nisn']; ?></td>
                    <td><?php echo $row['nis']; ?></td>
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $row['jk']; ?></td>
                    <td><?php echo $row['tempat_lahir']; ?></td>
                    <td><?php echo $row['tgl_lahir']; ?></td>
                    <td><?php echo $row['agama']; ?></td>
                    <td><?php echo $row['kelas']; ?></td>
                    <td><?php echo $row['jurusan']; ?></td>
                    <td><?php echo $row['nama_ayah']; ?></td>
                    <td><?php echo $row['nama_ibu']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="ttd">
        <p>Jakarta, <?php echo date('d F Y'); ?></p>
        <br><br>
        <p>Mengetahui,</p>
        <p>Kepala Sekolah,</p>
        <br><br><br>
        <p><strong>ADE SYAMSUDIN, M.Pd</strong></p>
    </div>

    <div class="footer">
        Dicetak dari Sistem Informasi Akademik SMA PGRI 4 Jakarta
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>