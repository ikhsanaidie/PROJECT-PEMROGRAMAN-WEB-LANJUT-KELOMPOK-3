<?php
// laporan/absensi_pdf.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
/** @var mysqli $conn */

// header('Content-Type: application/pdf');
// header('Content-Disposition: inline; filename="Laporan_Absensi.pdf"');

$kelas = $_GET['kelas'] ?? '';
$tanggal = $_GET['tanggal'] ?? date('Y-m-d');
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');
$mode = $_GET['mode'] ?? 'harian';

if (!$kelas) {
    header('Location: absensi.php');
    exit;
}

// Ambil wali kelas
$waliKelas = '-';
$wk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT wali_kelas FROM tbl_kelas WHERE nama_kelas = '$kelas'"));
if ($wk) {
    $g = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_guru FROM tbl_guru WHERE nip = '{$wk['wali_kelas']}'"));
    $waliKelas = $g['nama_guru'] ?? '-';
}

$siswaList = mysqli_query($conn, "SELECT * FROM tbl_siswa WHERE kelas = '$kelas' ORDER BY nama");

if (mysqli_num_rows($siswaList) == 0) {
    header('Location: absensi.php');
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Laporan Absensi</title>
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
            font-size: 10px;
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
            text-align: center;
        }

        .left-text {
            text-align: left;
            padding-left: 5px;
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

        .status-hadir {
            color: green;
            font-weight: bold;
        }

        .status-sakit {
            color: orange;
            font-weight: bold;
        }

        .status-izin {
            color: blue;
            font-weight: bold;
        }

        .status-alpa {
            color: red;
            font-weight: bold;
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
        <h2><?php echo $mode == 'harian' ? 'LAPORAN ABSENSI HARIAN' : 'REKAP ABSENSI BULANAN'; ?></h2>
    </div>

    <div class="info">
        <strong>Kelas:</strong> <?php echo $kelas; ?> &nbsp;|&nbsp;
        <strong>Wali Kelas:</strong> <?php echo $waliKelas; ?> &nbsp;|&nbsp;
        <?php if ($mode == 'harian'): ?>
            <strong>Tanggal:</strong> <?php echo $tanggal; ?>
        <?php else: ?>
            <strong>Periode:</strong> <?php echo date('F', mktime(0, 0, 0, $bulan, 1)); ?> <?php echo $tahun; ?>
        <?php endif; ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NISN</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Wali Kelas</th>
                <?php if ($mode == 'harian'): ?>
                    <th>Status</th>
                <?php else: ?>
                    <th>Hadir</th>
                    <th>Sakit</th>
                    <th>Izin</th>
                    <th>Alpa</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($siswaList)):
                if ($mode == 'harian') {
                    $status = '-';
                    $abs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM tbl_absensi WHERE tanggal = '$tanggal' AND nisn = '{$row['nisn']}'"));
                    if ($abs) $status = $abs['status'];
                    $cls = $status == 'Hadir' ? 'status-hadir' : ($status == 'Sakit' ? 'status-sakit' : ($status == 'Izin' ? 'status-izin' : ($status == 'Alpa' ? 'status-alpa' : '')));
            ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['nisn']; ?></td>
                        <td class="left-text"><?php echo $row['nama']; ?></td>
                        <td><?php echo $row['kelas']; ?></td>
                        <td><?php echo $waliKelas; ?></td>
                        <td class="<?php echo $cls; ?>"><?php echo $status; ?></td>
                    </tr>
                <?php
                } else {
                    $tglAwal = $tahun . '-' . $bulan . '-01';
                    $tglAkhir = $tahun . '-' . $bulan . '-' . date('t', strtotime($tglAwal));

                    $hadir = 0;
                    $sakit = 0;
                    $izin = 0;
                    $alpa = 0;
                    $absList = mysqli_query($conn, "SELECT status FROM tbl_absensi WHERE nisn = '{$row['nisn']}' AND tanggal BETWEEN '$tglAwal' AND '$tglAkhir'");
                    while ($a = mysqli_fetch_assoc($absList)) {
                        if ($a['status'] == 'Hadir') $hadir++;
                        elseif ($a['status'] == 'Sakit') $sakit++;
                        elseif ($a['status'] == 'Izin') $izin++;
                        elseif ($a['status'] == 'Alpa') $alpa++;
                    }
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['nisn']; ?></td>
                        <td class="left-text"><?php echo $row['nama']; ?></td>
                        <td><?php echo $row['kelas']; ?></td>
                        <td><?php echo $waliKelas; ?></td>
                        <td><?php echo $hadir; ?></td>
                        <td><?php echo $sakit; ?></td>
                        <td><?php echo $izin; ?></td>
                        <td><?php echo $alpa; ?></td>
                    </tr>
            <?php
                }
            endwhile;
            ?>
        </tbody>
    </table>

    <div class="ttd">
        <p>Jakarta, <?php echo date('d F Y'); ?></p>
        <br><br>
        <p>Mengetahui,</p>
        <p>Wali Kelas,</p>
        <br><br><br>
        <p><strong><?php echo $waliKelas; ?></strong></p>
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