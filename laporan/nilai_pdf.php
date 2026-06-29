<?php
// laporan/nilai_pdf.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
/** @var mysqli $conn */

// header('Content-Type: application/pdf');
// header('Content-Disposition: inline; filename="Laporan_Nilai.pdf"');

$kelas = $_GET['kelas'] ?? '';
$mapel = $_GET['mapel'] ?? '';
$search = $_GET['search'] ?? '';

$query = "SELECT n.*, s.kelas FROM tbl_nilai n LEFT JOIN tbl_siswa s ON n.nisn = s.nisn WHERE 1=1";
if ($kelas) $query .= " AND s.kelas = '$kelas'";
if ($mapel) $query .= " AND n.mata_pelajaran = '$mapel'";
if ($search) $query .= " AND (n.nisn LIKE '%$search%' OR n.nama_siswa LIKE '%$search%')";
$query .= " ORDER BY n.nisn";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: nilai.php');
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Laporan Nilai</title>
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
            padding: 4px 3px;
            border: 1px solid #000;
            text-align: center;
        }

        td {
            padding: 3px;
            border: 1px solid #000;
            text-align: center;
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

        .left-text {
            text-align: left;
            padding-left: 5px;
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
        <h2>LAPORAN NILAI SISWA</h2>
    </div>

    <div class="info">
        <?php if ($kelas): ?>
            <strong>Kelas:</strong> <?php echo $kelas; ?> &nbsp;|&nbsp;
        <?php endif; ?>
        <?php if ($mapel): ?>
            <strong>Mata Pelajaran:</strong> <?php echo $mapel; ?> &nbsp;|&nbsp;
        <?php endif; ?>
        <strong>Total:</strong> <?php echo mysqli_num_rows($result); ?> data &nbsp;|&nbsp;
        <strong>Dicetak:</strong> <?php echo date('d F Y H:i:s'); ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Guru</th>
                <th>Tugas</th>
                <th>UTS</th>
                <th>UAS</th>
                <th>Nilai Akhir</th>
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
                    <td class="left-text"><?php echo $row['nama_siswa']; ?></td>
                    <td><?php echo $row['kelas'] ?? '-'; ?></td>
                    <td class="left-text"><?php echo $row['mata_pelajaran']; ?></td>
                    <td class="left-text"><?php echo $row['guru_pengajar']; ?></td>
                    <td><?php echo $row['tugas']; ?></td>
                    <td><?php echo $row['uts']; ?></td>
                    <td><?php echo $row['uas']; ?></td>
                    <td><strong><?php echo number_format($row['nilai_akhir'], 2); ?></strong></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="ttd">
        <p>Jakarta, <?php echo date('d F Y'); ?></p>
        <br><br>
        <p>Mengetahui,</p>
        <p><?php echo $kelas ? 'Wali Kelas,' : 'Kepala Sekolah,'; ?></p>
        <br><br><br>
        <p><strong><?php echo $kelas ? 'Wali Kelas' : 'ADE SYAMSUDIN, M.Pd'; ?></strong></p>
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