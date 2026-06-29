<?php
// laporan/raport_pdf.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
/** @var mysqli $conn */

// header('Content-Type: application/pdf');
// header('Content-Disposition: inline; filename="Raport_Siswa.pdf"');

$nisn = $_GET['nisn'] ?? '';
$semester = $_GET['semester'] ?? 'Ganjil';
$tahun = $_GET['tahun'] ?? date('Y') . '/' . (date('Y') + 1);

$data_siswa = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tbl_siswa WHERE nisn = '$nisn'"));
if (!$data_siswa) {
    header('Location: raport.php');
    exit;
}

// Ambil wali kelas
$waliKelas = '-';
$wk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT wali_kelas FROM tbl_kelas WHERE nama_kelas = '{$data_siswa['kelas']}'"));
if ($wk) {
    $g = mysqli_fetch_assoc(mysqli_query($conn, "SELECT nama_guru FROM tbl_guru WHERE nip = '{$wk['wali_kelas']}'"));
    $waliKelas = $g['nama_guru'] ?? '-';
}

// Ambil nilai
$nilaiList = mysqli_query($conn, "SELECT * FROM tbl_nilai WHERE nisn = '$nisn' ORDER BY mata_pelajaran");

// Ambil absensi 6 bulan terakhir
$sixMonthsAgo = date('Y-m-d', strtotime('-6 months'));
$absenList = mysqli_query($conn, "SELECT status FROM tbl_absensi WHERE nisn = '$nisn' AND tanggal >= '$sixMonthsAgo'");
$sakit = 0;
$izin = 0;
$alpa = 0;
while ($a = mysqli_fetch_assoc($absenList)) {
    if ($a['status'] == 'Sakit') $sakit++;
    elseif ($a['status'] == 'Izin') $izin++;
    elseif ($a['status'] == 'Alpa') $alpa++;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Raport Siswa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            padding: 30px;
        }

        .kop {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop h1 {
            font-size: 20px;
            letter-spacing: 3px;
        }

        .kop p {
            font-size: 12px;
            color: #333;
        }

        .kop h2 {
            font-size: 15px;
            margin-top: 5px;
        }

        .info {
            margin-bottom: 15px;
        }

        .info table {
            width: 70%;
            font-size: 11px;
        }

        .info td {
            padding: 3px 5px;
        }

        .info .label {
            width: 120px;
            font-weight: bold;
        }

        table.nilai {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        table.nilai th {
            background: #1976D2;
            color: white;
            padding: 5px 4px;
            border: 1px solid #000;
            text-align: center;
        }

        table.nilai td {
            padding: 4px;
            border: 1px solid #000;
            text-align: center;
        }

        table.nilai .left {
            text-align: left;
            padding-left: 8px;
        }

        .ttd {
            margin-top: 40px;
        }

        .ttd table {
            width: 100%;
        }

        .ttd td {
            text-align: center;
            padding: 5px;
        }

        .ttd .line {
            border-bottom: 1px solid #000;
            width: 80%;
            margin: 10px auto 5px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }

        .catatan {
            margin: 15px 0;
            font-style: italic;
            font-size: 11px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
        }

        .rata {
            font-weight: bold;
            background: #f0f0f0;
        }

        .absensi-box {
            display: inline-block;
            padding: 5px 12px;
            margin: 2px;
            border-radius: 4px;
            font-size: 11px;
        }

        .absensi-sakit {
            background: #ffeb3b;
        }

        .absensi-izin {
            background: #2196f3;
            color: white;
        }

        .absensi-alpa {
            background: #f44336;
            color: white;
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
        <h2>LAPORAN HASIL BELAJAR (RAPORT)</h2>
    </div>

    <div class="info">
        <table>
            <tr>
                <td class="label">Nama Siswa</td>
                <td>: <?php echo $data_siswa['nama']; ?></td>
            </tr>
            <tr>
                <td class="label">NISN</td>
                <td>: <?php echo $data_siswa['nisn']; ?></td>
            </tr>
            <tr>
                <td class="label">Kelas</td>
                <td>: <?php echo $data_siswa['kelas']; ?></td>
            </tr>
            <tr>
                <td class="label">Wali Kelas</td>
                <td>: <?php echo $waliKelas; ?></td>
            </tr>
            <tr>
                <td class="label">Semester</td>
                <td>: <?php echo $semester; ?></td>
            </tr>
            <tr>
                <td class="label">Tahun Ajaran</td>
                <td>: <?php echo $tahun; ?></td>
            </tr>
        </table>
    </div>

    <div style="margin:10px 0;">
        <strong>Rekap Ketidakhadiran (6 Bulan Terakhir):</strong>
        <span class="absensi-box absensi-sakit">Sakit: <?php echo $sakit; ?> hari</span>
        <span class="absensi-box absensi-izin">Izin: <?php echo $izin; ?> hari</span>
        <span class="absensi-box absensi-alpa">Alpa: <?php echo $alpa; ?> hari</span>
    </div>

    <table class="nilai">
        <thead>
            <tr>
                <th>No</th>
                <th>Mata Pelajaran</th>
                <th>Guru</th>
                <th>Tugas</th>
                <th>UTS</th>
                <th>UAS</th>
                <th>Nilai Akhir</th>
                <th>Predikat</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $totalNilai = 0;
            $count = 0;
            while ($row = mysqli_fetch_assoc($nilaiList)):
                $totalNilai += $row['nilai_akhir'];
                $count++;
                $predikat = $row['nilai_akhir'] >= 90 ? 'A' : ($row['nilai_akhir'] >= 80 ? 'A-' : ($row['nilai_akhir'] >= 70 ? 'B+' : ($row['nilai_akhir'] >= 60 ? 'B' : 'C')));
            ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td class="left"><?php echo $row['mata_pelajaran']; ?></td>
                    <td class="left"><?php echo $row['guru_pengajar']; ?></td>
                    <td><?php echo $row['tugas']; ?></td>
                    <td><?php echo $row['uts']; ?></td>
                    <td><?php echo $row['uas']; ?></td>
                    <td><strong><?php echo number_format($row['nilai_akhir'], 2); ?></strong></td>
                    <td><strong><?php echo $predikat; ?></strong></td>
                </tr>
            <?php endwhile; ?>
            <?php if ($count == 0): ?>
                <tr>
                    <td colspan="8" style="text-align:center;padding:20px;">Belum ada data nilai</td>
                </tr>
            <?php else: ?>
                <tr class="rata">
                    <td colspan="6" style="text-align:right;">TOTAL / RATA-RATA</td>
                    <td><?php echo number_format($totalNilai / $count, 2); ?></td>
                    <td><?php echo $totalNilai / $count >= 90 ? 'A' : ($totalNilai / $count >= 80 ? 'A-' : ($totalNilai / $count >= 70 ? 'B+' : ($totalNilai / $count >= 60 ? 'B' : 'C'))); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="catatan">
        <strong>Catatan Wali Kelas:</strong><br>
        "Pertahankan prestasinya dan terus tingkatkan belajarnya!"
    </div>

    <div class="ttd">
        <table>
            <tr>
                <td>
                    <p>Mengetahui,</p>
                    <p><strong>Wali Kelas,</strong></p>
                    <div class="line"></div>
                    <p><strong><?php echo $waliKelas; ?></strong></p>
                </td>
                <td>
                    <p>Mengetahui,</p>
                    <p><strong>Wali Murid,</strong></p>
                    <div class="line"></div>
                    <p><strong>(_________________________)</strong></p>
                </td>
                <td>
                    <p>Jakarta, <?php echo date('d F Y'); ?></p>
                    <p>Mengetahui,</p>
                    <p><strong>Kepala Sekolah,</strong></p>
                    <div class="line"></div>
                    <p><strong>ADE SYAMSUDIN, M.Pd</strong></p>
                </td>
            </tr>
        </table>
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