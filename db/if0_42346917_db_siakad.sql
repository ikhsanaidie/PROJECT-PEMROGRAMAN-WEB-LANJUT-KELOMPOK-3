-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql202.infinityfree.com
-- Waktu pembuatan: 07 Jul 2026 pada 02.46
-- Versi server: 11.4.12-MariaDB
-- Versi PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_42346917_db_siakad`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_absensi`
--

CREATE TABLE `tbl_absensi` (
  `id_absensi` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `status` enum('Hadir','Sakit','Izin','Alpa','Tidak Hadir') DEFAULT 'Tidak Hadir'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_absensi`
--

INSERT INTO `tbl_absensi` (`id_absensi`, `tanggal`, `kelas`, `nisn`, `nama_siswa`, `status`) VALUES
(1, '2026-04-20', 'X MIPA 1', '12345', 'IKHSAN', 'Izin'),
(2, '2026-04-29', 'X MIPA 1', '12345', 'IKHSAN', 'Sakit'),
(3, '2026-04-29', 'X MIPA 1', '12346', 'FIRZA', 'Alpa'),
(4, '2026-04-30', 'X MIPA 1', '12347', 'ANI', 'Sakit'),
(5, '2026-04-30', 'X MIPA 1', '12346', 'FIRZA', 'Hadir'),
(6, '2026-04-30', 'X MIPA 1', '12345', 'IKHSAN AIDIE', 'Hadir'),
(13, '2026-05-05', 'X MIPA 1', '12347', 'ANI', 'Hadir'),
(14, '2026-05-05', 'X MIPA 1', '12346', 'FIRZA', 'Hadir'),
(15, '2026-05-05', 'X MIPA 1', '12345', 'IKHSAN AIDIE', 'Alpa'),
(16, '2026-05-07', 'X MIPA 1', '12347', 'ANI', 'Hadir'),
(17, '2026-05-07', 'X MIPA 1', '12346', 'FIRZA', 'Hadir'),
(18, '2026-05-07', 'X MIPA 1', '12345', 'IKHSAN AIDIE', 'Hadir'),
(19, '2026-06-08', 'X MIPA 1', '12347', 'ANI', 'Sakit'),
(20, '2026-06-08', 'X MIPA 1', '12346', 'FIRZA', 'Izin'),
(21, '2026-06-08', 'X MIPA 1', '12345', 'IKHSAN AIDIE', 'Hadir'),
(22, '2026-06-15', 'X MIPA 1', '12347', 'ANI', 'Sakit'),
(23, '2026-06-15', 'X MIPA 1', '12346', 'FIRZA', 'Alpa'),
(24, '2026-06-15', 'X MIPA 1', '12345', 'IKHSAN AIDIE', 'Hadir'),
(25, '2026-06-22', 'X MIPA 1', '12347', 'ANI', 'Hadir'),
(26, '2026-06-22', 'X MIPA 1', '12346', 'FIRZA', 'Alpa'),
(27, '2026-06-22', 'X MIPA 1', '12345', 'IKHSAN AIDIE', 'Sakit'),
(28, '2026-07-06', 'X MIPA 1', '12347', 'ANI', 'Hadir'),
(29, '2026-07-06', 'X MIPA 1', '12346', 'FIRZA', 'Hadir'),
(30, '2026-07-06', 'X MIPA 1', '12345', 'IKHSAN AIDIE', 'Hadir'),
(31, '2026-07-06', 'X MIPA 1', '202343502143', 'M FIRZA YUSTI P', 'Sakit'),
(32, '2026-07-06', 'X MIPA 1', '202343502130', 'M IKHSAN AIDIE', 'Hadir');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_event`
--

CREATE TABLE `tbl_event` (
  `id_event` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `tipe_event` varchar(50) DEFAULT NULL,
  `warna` varchar(20) DEFAULT NULL,
  `kelas_target` varchar(100) DEFAULT 'Semua Kelas',
  `dibuat_oleh` varchar(100) DEFAULT NULL,
  `tgl_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_event`
--

INSERT INTO `tbl_event` (`id_event`, `judul`, `deskripsi`, `tgl_mulai`, `tgl_selesai`, `tipe_event`, `warna`, `kelas_target`, `dibuat_oleh`, `tgl_dibuat`) VALUES
(1, 'UTS', 'UJIAN TENGAH SEMESTER', '2026-05-11', '2026-05-15', 'Ujian', 'hijau', 'Semua Kelas', 'Admin', '2026-05-07 10:13:49'),
(2, 'bazar', 'bazar makann', '2026-06-17', '2026-06-19', 'Akademik', NULL, 'Semua Kelas', 'Admin', '2026-06-15 13:52:21'),
(3, 'UAS', '', '2026-07-13', '2026-07-17', 'Akademik', NULL, 'Semua Kelas', 'Administrator', '2026-07-06 11:42:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_guru`
--

CREATE TABLE `tbl_guru` (
  `nip` varchar(20) NOT NULL,
  `jk` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `nama_guru` varchar(100) NOT NULL,
  `mapel` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT md5('guru123'),
  `last_login` datetime DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_guru`
--

INSERT INTO `tbl_guru` (`nip`, `jk`, `nama_guru`, `mapel`, `password`, `last_login`, `status`) VALUES
('12345670', 'Laki-laki', 'RANDY', 'SOSIOLOGI', '9310f83135f238b04af729fec041cca8', '2026-06-08 13:36:33', 'aktif'),
('12345678', 'Laki-laki', 'RIAN', 'BAHASA INDONESIA', '9310f83135f238b04af729fec041cca8', '2026-06-15 20:55:49', 'aktif'),
('1234579', 'Perempuan', 'SALLY', 'AGAMA ISLAM', 'd249cb4b774a96157a894e872fc0afd3', '2026-06-15 19:15:35', 'aktif'),
('202343502138', 'Perempuan', 'YULIARDA', 'MATEMATIKA', '1943102704f8f8f3302c2b730728e023', NULL, 'aktif'),
('202343502146', 'Perempuan', 'AULIA', 'BAHASA INDONESIA', '9310f83135f238b04af729fec041cca8', NULL, 'aktif'),
('202343502152', 'Laki-laki', 'RIFKI', 'SEJARAH', '9310f83135f238b04af729fec041cca8', NULL, 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kelas`
--

CREATE TABLE `tbl_kelas` (
  `id_kelas` varchar(20) NOT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `wali_kelas` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_kelas`
--

INSERT INTO `tbl_kelas` (`id_kelas`, `nama_kelas`, `wali_kelas`) VALUES
('001', 'X MIPA 1', '202343502138'),
('002', 'X IPS 1', '202343502152'),
('003', 'X BAHASA 1', '202343502146'),
('1', 'X MIPA 1', 'SITI'),
('11', 'X IPS 1', 'RANDY'),
('2', 'X MIPA 2', 'RINI');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_nilai`
--

CREATE TABLE `tbl_nilai` (
  `id_nilai` int(11) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `mata_pelajaran` varchar(50) DEFAULT NULL,
  `guru_pengajar` varchar(100) DEFAULT NULL,
  `tugas` decimal(5,2) DEFAULT 0.00,
  `uts` decimal(5,2) DEFAULT 0.00,
  `uas` decimal(5,2) DEFAULT 0.00,
  `nilai_akhir` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_nilai`
--

INSERT INTO `tbl_nilai` (`id_nilai`, `nisn`, `nama_siswa`, `mata_pelajaran`, `guru_pengajar`, `tugas`, `uts`, `uas`, `nilai_akhir`) VALUES
(1, '12345', 'IKHSAN AIDIE', 'AGAMA ISLAM', 'SALLY', '85.00', '90.00', '80.00', '84.00'),
(2, '12347', 'ANI', 'SOSIOLOGI', 'RANDY', '89.00', '75.00', '90.00', '85.30'),
(3, '202343502130', 'M IKHSAN AIDIE', 'MATEMATIKA', '', '70.00', '80.00', '80.00', '78.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pembayaran`
--

CREATE TABLE `tbl_pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `jenis_tagihan` enum('SPP','DAFTAR_ULANG','UANG_GEDUNG') NOT NULL DEFAULT 'SPP',
  `bulan` varchar(20) DEFAULT NULL,
  `total_tagihan` decimal(12,2) NOT NULL,
  `dibayar` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sisa_tagihan` decimal(12,2) DEFAULT 0.00,
  `status` enum('Lunas','Cicilan','Belum Bayar') DEFAULT 'Belum Bayar',
  `tgl_bayar` date DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `metode` varchar(20) DEFAULT 'Tunai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_pembayaran`
--

INSERT INTO `tbl_pembayaran` (`id_pembayaran`, `nisn`, `nama_siswa`, `jenis_tagihan`, `bulan`, `total_tagihan`, `dibayar`, `sisa_tagihan`, `status`, `tgl_bayar`, `catatan`, `metode`) VALUES
(1, '12345', 'IKHSAN AIDIE', 'SPP', 'April', '250000.00', '450000.00', '0.00', 'Lunas', '2026-06-22', '', 'Tunai'),
(2, '12346', 'FIRZA', 'UANG_GEDUNG', 'Januari', '1500000.00', '1500000.00', '0.00', 'Lunas', '2026-05-01', '', 'Tunai'),
(3, '12346', 'FIRZA', 'UANG_GEDUNG', 'Januari', '1500000.00', '1500000.00', '0.00', 'Lunas', '2026-05-01', '', 'Tunai'),
(4, '12346', 'FIRZA', 'UANG_GEDUNG', 'Januari', '1500000.00', '1500000.00', '0.00', 'Lunas', '2026-05-01', '', 'Tunai'),
(5, '12346', 'FIRZA', 'UANG_GEDUNG', 'Januari', '1500000.00', '1500000.00', '0.00', 'Lunas', '2026-06-13', '', 'Tunai'),
(6, '12346', 'FIRZA', 'UANG_GEDUNG', 'Januari', '1500000.00', '1500000.00', '0.00', 'Lunas', '2026-05-03', '', 'Tunai'),
(7, '12346', 'FIRZA', 'UANG_GEDUNG', 'Januari', '1500000.00', '1500000.00', '0.00', 'Lunas', '2026-05-03', '', 'Tunai'),
(8, '12346', 'FIRZA', 'SPP', 'Juli', '250000.00', '250000.00', '0.00', 'Lunas', '2026-06-13', NULL, 'Tunai'),
(9, '12347', 'ANI', 'SPP', 'Juli', '250000.00', '0.00', '250000.00', 'Belum Bayar', '2026-06-13', NULL, 'Tunai'),
(10, '12346', 'FIRZA', 'SPP', 'Agustus', '250000.00', '0.00', '250000.00', 'Belum Bayar', '2026-06-13', NULL, 'Tunai'),
(11, '12345', 'IKHSAN AIDIE', 'SPP', 'Juli', '250000.00', '600000.00', '0.00', 'Lunas', '2026-06-22', NULL, 'Tunai'),
(12, '12346', 'FIRZA', 'DAFTAR_ULANG', '-', '900000.00', '900000.00', '0.00', 'Lunas', '2026-06-15', NULL, 'Tunai'),
(13, '12345', 'IKHSAN AIDIE', 'SPP', 'Januari', '250000.00', '250000.00', '0.00', 'Lunas', '2026-06-22', NULL, 'Tunai'),
(14, '12345', 'IKHSAN AIDIE', 'SPP', 'Agustus', '250000.00', '250000.00', '0.00', 'Lunas', '2026-06-22', NULL, 'Transfer'),
(15, '12346', 'FIRZA', 'SPP', 'Januari', '250000.00', '250000.00', '0.00', 'Lunas', '2026-06-25', NULL, 'Tunai'),
(16, '202343502143', 'M FIRZA YUSTI P', 'SPP', 'Juli', '250000.00', '250000.00', '0.00', 'Lunas', '2026-07-06', NULL, 'Transfer');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_siswa`
--

CREATE TABLE `tbl_siswa` (
  `nisn` varchar(20) NOT NULL,
  `nis` varchar(20) DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `jk` enum('Laki-laki','Perempuan') NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `agama` enum('Islam','Kristen','Katolik','Hindu','Budha','Konghucu') NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `kelas` varchar(20) NOT NULL,
  `jurusan` varchar(30) DEFAULT NULL,
  `tahun_masuk` varchar(4) DEFAULT NULL,
  `nama_ayah` varchar(100) DEFAULT NULL,
  `nama_ibu` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_siswa`
--

INSERT INTO `tbl_siswa` (`nisn`, `nis`, `nama`, `jk`, `tempat_lahir`, `tgl_lahir`, `agama`, `alamat`, `no_hp`, `email`, `kelas`, `jurusan`, `tahun_masuk`, `nama_ayah`, `nama_ibu`) VALUES
('12345', '12346', 'IKHSAN AIDIE', 'Laki-laki', 'JAKARTA', '2005-04-26', 'Islam', 'JL CIRACAS', '088905592202', 'ikhsan262626@gmail.com', 'X MIPA 1', 'MIPA', '2026', 'YYYY', 'XXXX'),
('12346', '12345', 'FIRZA', 'Perempuan', 'JAKARTA', '2026-05-01', 'Islam', 'JL MANGGA', '', '', 'X MIPA 1', 'MIPA', '2026', '', ''),
('12347', '1248', 'ANI', 'Perempuan', 'BANDUNG', '2006-04-30', 'Kristen', 'JL BANDUNG', '089789021', 'XXX@GMAIL.COM', 'X MIPA 1', 'MIPA', '2026', 'SSSS', 'ZZZ'),
('12348', '12347', 'HAMDI', 'Laki-laki', 'JAKARTA', '2004-05-02', 'Islam', 'JL LEBAK BULUS', '0897890097262', 'HAMDI@GMAIL.COM', 'X IPS 1', 'IPS', '2026', 'ROI', 'YOI'),
('202343502130', '2130', 'M IKHSAN AIDIE', 'Laki-laki', 'JAKARTA', '2005-04-26', 'Islam', 'JL CIRACAS', '088905592202', 'IKHSAN262626@GMAIL.COM', 'X MIPA 1', 'MIPA', '2023', 'MAS W', 'MBA X'),
('202343502143', '2143', 'M FIRZA YUSTI P', 'Laki-laki', 'JAKARTA', '2005-04-27', 'Islam', 'JL TRIKORA', '081285124312', 'FIRZA2143@GMAIL.COM', 'X MIPA 1', 'MIPA', '2023', 'MAS T', 'MBA A'),
('202343502153', '2153', 'AQILAH NAINA', 'Perempuan', 'BOGOR', '2005-04-28', 'Islam', 'JL CIBINONG', '08123456789', 'AQILAH2153@GMAIL.COM', 'X IPS 1', 'IPS', '2023', 'MAS N', 'MBA P');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_spp`
--

CREATE TABLE `tbl_spp` (
  `id_spp` int(11) NOT NULL,
  `nisn` varchar(20) NOT NULL,
  `nama_siswa` varchar(100) NOT NULL,
  `bulan` varchar(20) NOT NULL,
  `nominal` decimal(12,2) NOT NULL,
  `status` enum('Lunas','Belum') DEFAULT 'Belum',
  `tgl_bayar` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kepsek') DEFAULT 'admin',
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `username`, `password`, `role`, `nama_lengkap`, `last_login`, `status`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin', 'Administrator', '2026-06-22 18:13:39', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `nisn` (`nisn`);

--
-- Indeks untuk tabel `tbl_event`
--
ALTER TABLE `tbl_event`
  ADD PRIMARY KEY (`id_event`);

--
-- Indeks untuk tabel `tbl_guru`
--
ALTER TABLE `tbl_guru`
  ADD PRIMARY KEY (`nip`);

--
-- Indeks untuk tabel `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indeks untuk tabel `tbl_nilai`
--
ALTER TABLE `tbl_nilai`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `nisn` (`nisn`);

--
-- Indeks untuk tabel `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `nisn` (`nisn`);

--
-- Indeks untuk tabel `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD PRIMARY KEY (`nisn`);

--
-- Indeks untuk tabel `tbl_spp`
--
ALTER TABLE `tbl_spp`
  ADD PRIMARY KEY (`id_spp`),
  ADD KEY `nisn` (`nisn`);

--
-- Indeks untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  MODIFY `id_absensi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `tbl_event`
--
ALTER TABLE `tbl_event`
  MODIFY `id_event` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tbl_nilai`
--
ALTER TABLE `tbl_nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `tbl_spp`
--
ALTER TABLE `tbl_spp`
  MODIFY `id_spp` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  ADD CONSTRAINT `tbl_absensi_ibfk_1` FOREIGN KEY (`nisn`) REFERENCES `tbl_siswa` (`nisn`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_nilai`
--
ALTER TABLE `tbl_nilai`
  ADD CONSTRAINT `tbl_nilai_ibfk_1` FOREIGN KEY (`nisn`) REFERENCES `tbl_siswa` (`nisn`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
  ADD CONSTRAINT `tbl_pembayaran_ibfk_1` FOREIGN KEY (`nisn`) REFERENCES `tbl_siswa` (`nisn`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_spp`
--
ALTER TABLE `tbl_spp`
  ADD CONSTRAINT `tbl_spp_ibfk_1` FOREIGN KEY (`nisn`) REFERENCES `tbl_siswa` (`nisn`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
