-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 06, 2024 at 09:11 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `baznas`
--

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa_luar_negeri`
--

CREATE TABLE `mahasiswa_luar_negeri` (
  `ID` int(100) NOT NULL,
  `Nomor_Registrasi` varchar(10) NOT NULL,
  `Jenis_Pembagian` varchar(255) NOT NULL,
  `Nama` varchar(255) NOT NULL,
  `Nomor_KTP` varchar(255) NOT NULL,
  `Tempat_Lahir` varchar(255) NOT NULL,
  `Tanggal_Lahir` date NOT NULL,
  `Jenis_Kelamin` enum('Laki-Laki','Perempuan','','') NOT NULL,
  `Pekerjaan` varchar(255) NOT NULL,
  `Alamat` varchar(255) NOT NULL,
  `Kecamatan` varchar(255) NOT NULL,
  `Kabupaten` varchar(255) NOT NULL,
  `No_Hp` varchar(20) NOT NULL,
  `Submit` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa_luar_negeri`
--

INSERT INTO `mahasiswa_luar_negeri` (`ID`, `Nomor_Registrasi`, `Jenis_Pembagian`, `Nama`, `Nomor_KTP`, `Tempat_Lahir`, `Tanggal_Lahir`, `Jenis_Kelamin`, `Pekerjaan`, `Alamat`, `Kecamatan`, `Kabupaten`, `No_Hp`, `Submit`) VALUES
(33, '002', 'mahasiswa_luar_negeri', 'M.Syarifudin', '2101020070707070', 'Sondosia', '2002-04-22', 'Laki-Laki', 'Mahasiswa', 'Sondosia', 'Bolo', 'Bima', '022', '2024-07-24'),
(34, '005', 'mahasiswa_luar_negeri', 'Raihan Firman', '2001020069696969', 'Mataram', '2001-12-12', 'Laki-Laki', 'Mahasiswa', 'Mataram', 'Mataram', 'Mataram', '12', '2024-07-27');

-- --------------------------------------------------------

--
-- Table structure for table `muallaf`
--

CREATE TABLE `muallaf` (
  `ID` int(11) NOT NULL,
  `Nomor_Registrasi` varchar(10) NOT NULL,
  `Jenis_Pembagian` varchar(255) NOT NULL,
  `Nama` varchar(255) NOT NULL,
  `Nomor_KTP` varchar(255) NOT NULL,
  `Tempat_Lahir` varchar(255) NOT NULL,
  `Tanggal_Lahir` date NOT NULL,
  `Jenis_Kelamin` enum('Laki-Laki','Perempuan','','') NOT NULL,
  `Pekerjaan` varchar(255) NOT NULL,
  `Alamat` varchar(255) NOT NULL,
  `Kecamatan` varchar(255) NOT NULL,
  `Kabupaten` varchar(255) NOT NULL,
  `No_Hp` varchar(20) NOT NULL,
  `Submit` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `muallaf`
--

INSERT INTO `muallaf` (`ID`, `Nomor_Registrasi`, `Jenis_Pembagian`, `Nama`, `Nomor_KTP`, `Tempat_Lahir`, `Tanggal_Lahir`, `Jenis_Kelamin`, `Pekerjaan`, `Alamat`, `Kecamatan`, `Kabupaten`, `No_Hp`, `Submit`) VALUES
(119, '001', 'muallaf', 'Adi Suryadin', '2101020001010101', 'Bima', '2002-01-01', 'Laki-Laki', 'Mahasiswa', 'Ncera', 'Belo', 'Bima', '011', '2024-07-24'),
(121, '004', 'muallaf', 'Onis Alamsyah', '2909090909090909', 'Dompu', '2002-12-01', 'Laki-Laki', 'Mahasiswa', 'Dompu', 'Dompu', 'Dompu', '12', '2024-07-27'),
(127, '006', 'muallaf', 'Heri Sumantri', '2101020079797979', 'Dompu', '2001-02-02', 'Laki-Laki', 'Wiraswasta', 'Dompu', 'Dompu', 'Dompu', '21', '2024-07-30');

-- --------------------------------------------------------

--
-- Table structure for table `pencairan`
--

CREATE TABLE `pencairan` (
  `Nomor_Registrasi` varchar(10) NOT NULL,
  `Jenis_Pembagian` varchar(255) NOT NULL,
  `Nama` varchar(255) NOT NULL,
  `Nomor_KTP` varchar(255) NOT NULL,
  `Alamat` varchar(255) NOT NULL,
  `Jenis_Dana` varchar(255) NOT NULL,
  `Via` varchar(255) NOT NULL,
  `Jumlah_Dana` bigint(20) NOT NULL,
  `Keterangan` varchar(255) NOT NULL,
  `Submit` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pencairan`
--

INSERT INTO `pencairan` (`Nomor_Registrasi`, `Jenis_Pembagian`, `Nama`, `Nomor_KTP`, `Alamat`, `Jenis_Dana`, `Via`, `Jumlah_Dana`, `Keterangan`, `Submit`) VALUES
('001', 'muallaf', 'Adi Suryadin', '2101020001010101', 'Ncera', 'zakat', 'zakat', 9999999, 'Bantuan Muallaf A.n Adi Suryadin alamat Desa Ncera Kecamatan Belo Kabupaten Bima Yang diterima adiknya A.n Muhammad abdul', ''),
('002', 'mahasiswa_luar_negeri', 'M.Syarifudin', '2101020070707070', 'Sondosia', 'zakat', 'zakat', 12000000, 'Bantuan Studi Di Luar Negeri Tujuan Australia A.n M.Syarifudin Alamat Desa Sondosia Kecamatan Bolo Kabupaten Bima Yang Diterima dirinya pribadi', ''),
('003', 'penelitian', 'Nindya Alifia Khumaira', '2101020021212121', 'Kumbe', 'zakat', 'zakat', 9999999, 'bantuan penelitin s1 universitas bumigora a.n nindya alifia khumaira alamat desan kumbe kecamatan kumbe kota bima yang diterima oleh dirinya sendiri dengan nominal sembilan juta sembila ratus sembilan puluh sembilan', ''),
('005', 'mahasiswa_luar_negeri', 'Raihan Firman', '2001020069696969', 'Mataram', 'zakat', 'zakat', 2777700, 'Bantuan Pengadaaan Al-Quran untuk yayasan gerakan 1000 al-quran yang beralamat di desa jawa timur kecamatan jawa timur kab. jawa timur provinsi jawa timur yang diterima langsung oleh ketuannya an syekh a bla bla bla', '');

-- --------------------------------------------------------

--
-- Table structure for table `pencairank`
--

CREATE TABLE `pencairank` (
  `Nomor_Registrasi` varchar(10) NOT NULL,
  `Jenis_Penerima` varchar(255) NOT NULL,
  `Nama_Lembaga` varchar(255) NOT NULL,
  `NIK_Pimpinan` varchar(255) NOT NULL,
  `Nama_Pimpinan` varchar(255) NOT NULL,
  `Alamat` text NOT NULL,
  `Jenis_Dana` varchar(255) NOT NULL,
  `Via` varchar(255) NOT NULL,
  `Jumlah_Dana` bigint(20) NOT NULL,
  `Keterangan` text NOT NULL,
  `Submit` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pencairank`
--

INSERT INTO `pencairank` (`Nomor_Registrasi`, `Jenis_Penerima`, `Nama_Lembaga`, `NIK_Pimpinan`, `Nama_Pimpinan`, `Alamat`, `Jenis_Dana`, `Via`, `Jumlah_Dana`, `Keterangan`, `Submit`) VALUES
('A001', 'sabilillah', 'Yayasan Peduli Anak', '1212121212121212', 'Abdul Qadir', 'Karang Tapen', 'zakat', 'zakat', 2000000, 'Bantuan bagi anak yg kurang mampu di karang tapen alamat Desa karang tapen kec. cakrangera kota mataram yang diterima ketuan A.n Abdul Qodir', ''),
('A002', 'tpq', 'Yayasan Peduli Bapak', '1414141414141414', 'Muhaemin', 'Gunung Sari', 'zakat', 'zakat', 2000000, 'Bantuan Biaya untuk Bapak-Bapak alamat desa gunungsari kec. lobar kabupaten lombok barat yg diterima langsung oleh ketuanya A.n Muhaemin', ''),
('A004', 'tpq', 'Gerakan 1000 Quran', '4545454545454545', 'Syekh A', 'Jawa Timur', 'zakat', 'zakat', 9090700, 'Bantuan Pengadaaan Al-Quran untuk yayasan gerakan 1000 al-quran yang beralamat di desa jawa timur kecamatan jawa timur kab. jawa timur provinsi jawa timur yang diterima langsung oleh ketuannya an syekh a bla bla bla', '');

-- --------------------------------------------------------

--
-- Table structure for table `penelitian`
--

CREATE TABLE `penelitian` (
  `ID` int(11) NOT NULL,
  `Nomor_Registrasi` varchar(10) NOT NULL,
  `Jenis_Pembagian` varchar(255) NOT NULL,
  `Nama` varchar(255) NOT NULL,
  `Nomor_KTP` varchar(255) NOT NULL,
  `Tempat_Lahir` varchar(255) NOT NULL,
  `Tanggal_Lahir` date NOT NULL,
  `Jenis_Kelamin` enum('Laki-Laki','Perempuan','','') NOT NULL,
  `Pekerjaan` varchar(255) NOT NULL,
  `Alamat` varchar(255) NOT NULL,
  `Kecamatan` varchar(255) NOT NULL,
  `Kabupaten` varchar(255) NOT NULL,
  `No_Hp` varchar(20) NOT NULL,
  `Submit` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penelitian`
--

INSERT INTO `penelitian` (`ID`, `Nomor_Registrasi`, `Jenis_Pembagian`, `Nama`, `Nomor_KTP`, `Tempat_Lahir`, `Tanggal_Lahir`, `Jenis_Kelamin`, `Pekerjaan`, `Alamat`, `Kecamatan`, `Kabupaten`, `No_Hp`, `Submit`) VALUES
(25, '003', 'penelitian', 'Nindya Alifia Khumaira', '2101020021212121', 'Kumbe', '2002-02-22', 'Perempuan', 'Mahasiswa', 'Kumbe', 'Asakota', 'Bima', '044', '2024-07-24');

-- --------------------------------------------------------

--
-- Table structure for table `sabilillah`
--

CREATE TABLE `sabilillah` (
  `Nomor_Registrasi` varchar(10) NOT NULL,
  `Nama_Lembaga` varchar(255) NOT NULL,
  `Jenis_Penerima` varchar(255) NOT NULL,
  `NIK_Pimpinan` varchar(255) NOT NULL,
  `Nama_Pimpinan` varchar(255) NOT NULL,
  `Jenis_Lembaga` varchar(255) NOT NULL,
  `Jumlah_Anggota` int(11) NOT NULL,
  `Alamat` varchar(255) NOT NULL,
  `Kecamatan` varchar(255) NOT NULL,
  `Kabupaten` varchar(255) NOT NULL,
  `No_Hp` varchar(20) NOT NULL,
  `Submit` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sabilillah`
--

INSERT INTO `sabilillah` (`Nomor_Registrasi`, `Nama_Lembaga`, `Jenis_Penerima`, `NIK_Pimpinan`, `Nama_Pimpinan`, `Jenis_Lembaga`, `Jumlah_Anggota`, `Alamat`, `Kecamatan`, `Kabupaten`, `No_Hp`, `Submit`) VALUES
('A001', 'Yayasan Peduli Anak', 'sabilillah', '1212121212121212', 'Abdul Qadir', 'musholla', 10, 'Karang Tapen', 'Cakranegara', 'Mataram', '0222', '2024-07-24'),
('A003', 'Yayasan Ponpes Iman', 'sabilillah', '9090909090999909', 'Abdul Muhae', 'musholla', 10, 'Lotim', 'Pringgasela', 'Lotim', '12', '2024-07-27'),
('A005', 'Yayasan Quran Madani', 'sabilillah', '2121212121212121', 'Abdul Hamid', 'musholla', 10, 'Bima', 'Bolo', 'Bima', '2', '2024-07-30');

-- --------------------------------------------------------

--
-- Table structure for table `tpq`
--

CREATE TABLE `tpq` (
  `Nomor_Registrasi` varchar(10) NOT NULL,
  `Nama_Lembaga` varchar(255) NOT NULL,
  `Jenis_Penerima` varchar(255) NOT NULL,
  `NIK_Pimpinan` varchar(255) NOT NULL,
  `Nama_Pimpinan` varchar(255) NOT NULL,
  `Jenis_Lembaga` varchar(255) NOT NULL,
  `Jumlah_Anggota` int(11) NOT NULL,
  `Alamat` varchar(255) NOT NULL,
  `Kecamatan` varchar(255) NOT NULL,
  `Kabupaten` varchar(255) NOT NULL,
  `No_Hp` varchar(20) NOT NULL,
  `Submit` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tpq`
--

INSERT INTO `tpq` (`Nomor_Registrasi`, `Nama_Lembaga`, `Jenis_Penerima`, `NIK_Pimpinan`, `Nama_Pimpinan`, `Jenis_Lembaga`, `Jumlah_Anggota`, `Alamat`, `Kecamatan`, `Kabupaten`, `No_Hp`, `Submit`) VALUES
('A002', 'Yayasan Peduli Bapak', 'tpq', '1414141414141414', 'Muhaemin', 'masjid', 10, 'Gunung Sari', 'Gunung Sari', 'Lombok Barat', '045', '2024-07-24'),
('A004', 'Gerakan 1000 Quran', 'tpq', '4545454545454545', 'Syekh A', 'masjid', 10, 'Jawa Timur', 'jatim', 'jatim', '46', '2024-07-27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mahasiswa_luar_negeri`
--
ALTER TABLE `mahasiswa_luar_negeri`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `muallaf`
--
ALTER TABLE `muallaf`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `penelitian`
--
ALTER TABLE `penelitian`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `sabilillah`
--
ALTER TABLE `sabilillah`
  ADD PRIMARY KEY (`Nama_Lembaga`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mahasiswa_luar_negeri`
--
ALTER TABLE `mahasiswa_luar_negeri`
  MODIFY `ID` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `muallaf`
--
ALTER TABLE `muallaf`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `penelitian`
--
ALTER TABLE `penelitian`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
