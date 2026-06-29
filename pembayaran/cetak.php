<?php
// pembayaran/cetak.php
session_start();
include '../config/koneksi.php';

$nisn = $_GET['nisn'] ?? '';
$data = mysqli_query($conn, "SELECT * FROM tbl_pembayaran WHERE nisn = '$nisn' ORDER BY id_pembayaran DESC LIMIT 1");
$row = mysqli_fetch_assoc($data);

if (!$row) {
    header('Location: index.php');
    exit;
}

// Header PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="kwitansi_' . $row['nisn'] . '.pdf"');

// Simple PDF using fPDF (if available) or HTML to PDF
// Untuk demo, kita tampilkan HTML yang bisa di-print
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kwitansi Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .kop { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop h1 { margin: 0; font-size: 18px; }
        .kop p { margin: 2px 0; font-size: 12px; color: #666; }
        .info { margin: 20px 0; }
        .info table { width: 100%; }
        .info td { padding: 5px; }
        .total { margin: 20px 0; }
        .total table { width: 60%; float: right; }
        .total td { padding: 5px; }
        .ttd { margin-top: 50px; }
        .ttd table { width: 100%; }
        .ttd td { text-align: center; padding-top: 30px; }
        .ttd .line { border-bottom: 1px solid #000; width: 80%; margin: 0 auto; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <button class="no-print" onclick="window.print()">🖨️ Cetak</button>
    <button class="no-print" onclick="window.close()">✖ Tutup</button>
    
    <div class="kop">
        <h1>SMA PGRI 4 JAKARTA</h1>
        <p>Jl. Cipayung Raya, Jakarta Timur 13840</p>
        <h2>BUKTI PEMBAYARAN</h2>
    </div>
    
    <div class="info">
        <table>
            <tr><td width="120"><strong>No Transaksi</strong></td><td>: TRX/<?php echo $row['jenis_tagihan']; ?>/<?php echo date('Ymd'); ?>/<?php echo $row['id_pembayaran']; ?></td></tr>
            <tr><td><strong>Tanggal</strong></td><td>: <?php echo date('d-m-Y H:i:s'); ?></td></tr>
            <tr><td><strong>NISN</strong></td><td>: <?php echo $row['nisn']; ?></td></tr>
            <tr><td><strong>Nama</strong></td><td>: <?php echo $row['nama_siswa']; ?></td></tr>
            <tr><td><strong>Metode</strong></td><td>: <?php echo $row['metode'] ?? 'Tunai'; ?></td></tr>
        </table>
    </div>
    
    <hr>
    
    <table style="width:100%;border-collapse:collapse;">
        <tr style="background:#f0f0f0;">
            <th style="padding:8px;border:1px solid #ddd;">No</th>
            <th style="padding:8px;border:1px solid #ddd;">Nama Pembayaran</th>
            <th style="padding:8px;border:1px solid #ddd;">Bulan</th>
            <th style="padding:8px;border:1px solid #ddd;">Nominal</th>
        </tr>
        <tr>
            <td style="padding:8px;border:1px solid #ddd;text-align:center;">1</td>
            <td style="padding:8px;border:1px solid #ddd;"><?php echo $row['jenis_tagihan']; ?></td>
            <td style="padding:8px;border:1px solid #ddd;text-align:center;"><?php echo $row['bulan'] ?? '-'; ?></td>
            <td style="padding:8px;border:1px solid #ddd;text-align:right;"><?php echo number_format($row['dibayar'], 0, ',', '.'); ?></td>
        </tr>
    </table>
    
    <div class="total">
        <table>
            <tr><td><strong>Total Tagihan</strong></td><td style="text-align:right;"><?php echo number_format($row['total_tagihan'], 0, ',', '.'); ?></td></tr>
            <tr><td><strong>Dibayar</strong></td><td style="text-align:right;"><?php echo number_format($row['dibayar'], 0, ',', '.'); ?></td></tr>
            <tr><td><strong>Sisa Tagihan</strong></td><td style="text-align:right;"><?php echo number_format($row['sisa_tagihan'], 0, ',', '.'); ?></td></tr>
            <tr><td><strong>Status</strong></td><td style="text-align:right;"><?php echo $row['status']; ?></td></tr>
        </table>
    </div>
    
    <hr style="clear:both;">
    
    <div class="ttd">
        <table>
            <tr>
                <td style="text-align:right;">
                    <p>Jakarta, <?php echo date('d F Y'); ?></p>
                    <p><strong>Bendahara,</strong></p>
                    <br><br>
                    <p><strong>NIA</strong></p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>