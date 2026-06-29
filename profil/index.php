<?php
// profil/index.php
session_start();
if (!isset($_SESSION['role'])) {
    header('Location: ../auth/login.php');
    exit;
}
include '../config/koneksi.php';
include '../includes/header.php';

// Ambil data profil dari database (jika ada) atau hardcode
// Di sini saya hardcode karena data profil sekolah jarang berubah
?>
<style>
.profil-container {
    max-width: 1000px;
    margin: 0 auto;
}
.profil-hero {
    background: white;
    border-radius: 12px;
    padding: 40px;
    text-align: center;
    border: 1px solid #E0E0E0;
    margin-bottom: 25px;
}
.profil-hero img {
    width: 100px;
    height: 100px;
    object-fit: contain;
}
.profil-hero h1 {
    font-size: 28px;
    color: #1976D2;
    margin: 10px 0 5px;
}
.profil-hero .tagline {
    font-size: 14px;
    color: #666;
}
.profil-hero .detail {
    font-size: 12px;
    color: #999;
    margin-top: 5px;
}

.profil-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 25px;
}

.profil-card {
    background: white;
    border-radius: 12px;
    padding: 25px 30px;
    border: 1px solid #E0E0E0;
}
.profil-card .card-icon {
    font-size: 32px;
    display: block;
    margin-bottom: 10px;
}
.profil-card h3 {
    color: #1976D2;
    font-size: 16px;
    margin-bottom: 10px;
}
.profil-card p, .profil-card li {
    font-size: 13px;
    color: #444;
    line-height: 1.8;
}
.profil-card ul {
    padding-left: 20px;
    margin: 0;
}
.profil-card ul li {
    list-style: decimal;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
}

.contact-card {
    background: white;
    border-radius: 12px;
    padding: 20px 25px;
    border: 1px solid #E0E0E0;
}
.contact-card h4 {
    color: #1976D2;
    font-size: 15px;
    margin-bottom: 8px;
}
.contact-card p {
    font-size: 13px;
    color: #444;
    line-height: 1.6;
    margin: 4px 0;
}
.contact-card .icon {
    font-size: 18px;
    margin-right: 8px;
}

@media (max-width: 768px) {
    .profil-grid, .contact-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="page-header">
    <h2>🏫 Profil Sekolah</h2>
</div>

<div class="profil-container">
    <!-- Hero Card -->
    <div class="profil-hero">
        <img src="../assets/img/smapgri4.png" alt="Logo SMA PGRI 4 Jakarta">
        <h1>SMA PGRI 4 JAKARTA</h1>
        <p class="tagline">Sekolah Unggulan Terakreditasi A</p>
        <p class="detail">NPSN: 20123456 | Status: Swasta | Akreditasi: A</p>
    </div>

    <!-- Visi & Misi -->
    <div class="profil-grid">
        <div class="profil-card">
            <span class="card-icon">🎯</span>
            <h3>Visi</h3>
            <p>Menjadi sekolah unggul yang menghasilkan lulusan beriman, bertaqwa, berilmu, berakhlak mulia, dan berdaya saing global di era digital.</p>
        </div>
        <div class="profil-card">
            <span class="card-icon">📋</span>
            <h3>Misi</h3>
            <ul>
                <li>Meningkatkan kualitas pembelajaran berbasis teknologi informasi.</li>
                <li>Mengembangkan potensi siswa melalui kegiatan ekstrakurikuler.</li>
                <li>Membangun karakter siswa yang berakhlak mulia.</li>
                <li>Menjalin kerjasama dengan dunia usaha dan industri.</li>
                <li>Meningkatkan profesionalisme tenaga pendidik dan kependidikan.</li>
            </ul>
        </div>
    </div>

    <!-- Sejarah -->
    <div class="profil-card" style="margin-bottom:25px;">
        <span class="card-icon">📖</span>
        <h3>Sejarah Singkat</h3>
        <p>SMA PGRI 4 Jakarta berdiri sejak tahun 1981, memiliki luas tanah 1.150 m² merupakan sekolah yang memiliki reputasi baik dan diakui kualitasnya. Terakreditasi A pada tanggal 22 Juni 2020 dengan No SK 458/BAN-SM/sk/2020. Hal ini menandakan bahwa SMA PGRI 4 Jakarta berkomitmen untuk memberikan pendidikan berkualitas tinggi dan memenuhi standar nasional pendidikan.</p>
    </div>

    <!-- Kontak & Lokasi -->
    <div class="contact-grid">
        <div class="contact-card">
            <h4>📍 Lokasi</h4>
            <p>Jl. Cipayung Raya, RT.1/RW.3</p>
            <p>Kel. Cipayung, Kec. Cipayung</p>
            <p>Jakarta Timur 13840</p>
        </div>
        <div class="contact-card">
            <h4>📞 Kontak</h4>
            <p><span class="icon">📞</span> (021) 2287 6501</p>
            <p><span class="icon">✉️</span> info@smapgri4jakarta.sch.id</p>
            <p><span class="icon">🌐</span> www.smapgri4jakarta.sch.id</p>
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div style="text-align:center;margin-top:25px;">
        <a href="../dashboard/" class="btn btn-primary">◀ KEMBALI KE DASHBOARD</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>