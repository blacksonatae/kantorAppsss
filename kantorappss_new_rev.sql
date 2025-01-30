-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2025 at 03:19 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kantorappss`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensis`
--

CREATE TABLE `absensis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `waktu_masuk` timestamp NULL DEFAULT NULL,
  `waktu_pulang` timestamp NULL DEFAULT NULL,
  `status_absensi_masuk` enum('Hadir','Izin','Alpha') DEFAULT NULL,
  `status_absensi_pulang` enum('Pulang Cepat','Pulang','Lembur','-') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensis`
--

INSERT INTO `absensis` (`id`, `user_id`, `waktu_masuk`, `waktu_pulang`, `status_absensi_masuk`, `status_absensi_pulang`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-12-01 02:30:02', '2024-12-01 11:10:02', 'Hadir', 'Pulang', '2024-12-01 02:30:02', '2024-12-01 11:10:02'),
(2, 2, '2024-12-01 02:30:02', '2024-12-01 11:10:02', 'Hadir', 'Pulang', '2024-12-01 02:30:02', '2024-12-01 11:10:02'),
(3, 1, '2024-12-02 02:30:02', '2024-12-02 11:10:02', 'Hadir', 'Pulang', '2024-12-02 02:30:02', '2024-12-02 11:10:02'),
(4, 2, '2024-12-02 02:30:02', '2024-12-02 11:10:02', 'Hadir', 'Pulang', '2024-12-02 02:30:02', '2024-12-02 11:10:02'),
(5, 1, '2024-12-03 02:30:02', '2024-12-03 11:10:02', 'Hadir', 'Pulang', '2024-12-03 02:30:02', '2024-12-03 11:10:02'),
(6, 2, '2024-12-03 02:30:02', '2024-12-03 11:10:02', 'Hadir', 'Pulang', '2024-12-03 02:30:02', '2024-12-03 11:10:02'),
(7, 1, '2024-12-04 02:30:02', '2024-12-04 13:10:02', 'Hadir', 'Lembur', '2024-12-04 02:30:02', '2024-12-04 13:10:02'),
(8, 2, '2024-12-04 02:30:02', '2024-12-04 09:10:02', 'Hadir', 'Pulang Cepat', '2024-12-04 02:30:02', '2024-12-04 09:10:02'),
(9, 1, '2024-12-05 02:30:02', '2024-12-05 09:30:02', 'Hadir', 'Pulang Cepat', '2024-12-05 02:30:02', '2024-12-05 09:30:02'),
(10, 2, '2024-12-05 02:30:02', '2024-12-05 08:30:02', 'Hadir', 'Pulang Cepat', '2024-12-05 02:30:02', '2024-12-05 08:30:02'),
(11, 1, '2024-12-06 02:30:02', '2024-12-06 13:35:02', 'Hadir', 'Lembur', '2024-12-06 02:30:02', '2024-12-06 13:35:02'),
(12, 2, '2024-12-06 02:35:02', '2024-12-06 08:00:02', 'Hadir', 'Pulang Cepat', '2024-12-06 02:35:02', '2024-12-06 08:00:02'),
(13, 1, '2024-12-07 02:35:30', '2024-12-07 02:35:30', 'Izin', '-', '2024-12-07 02:35:30', '2024-12-07 02:35:30'),
(14, 2, '2024-12-07 02:35:30', '2024-12-07 02:35:30', 'Alpha', '-', '2024-12-07 02:35:30', '2024-12-07 02:35:30'),
(15, 1, '2024-12-08 02:35:30', '2024-12-08 11:35:30', 'Hadir', 'Pulang', '2024-12-08 02:35:30', '2024-12-08 11:35:30'),
(16, 2, '2024-12-08 02:35:30', '2024-12-09 11:35:30', 'Izin', '-', '2024-12-08 02:35:30', '2024-12-08 02:35:30'),
(21, 1, '2024-12-09 02:35:30', '2024-12-09 02:35:30', 'Izin', '-', '2024-12-08 02:35:30', '2024-12-08 02:35:30'),
(22, 2, '2024-12-09 02:35:30', '2024-12-09 02:35:30', 'Alpha', '-', '2024-12-09 02:35:30', '2024-12-09 02:35:30'),
(23, 1, '2024-12-10 02:35:30', '2024-12-10 14:35:30', 'Hadir', 'Lembur', '2024-12-10 02:35:30', '2024-12-10 14:35:30'),
(24, 2, '2024-12-10 02:35:30', '2024-12-10 02:35:30', 'Alpha', '-', '2024-12-10 02:35:30', '2024-12-10 02:35:30'),
(25, 1, '2024-12-11 02:35:30', '2024-12-11 11:20:30', 'Hadir', 'Pulang', '2024-12-11 02:35:30', '2024-12-11 11:20:30'),
(26, 2, '2024-12-11 02:35:30', '2024-12-11 02:35:30', 'Alpha', '-', '2024-12-11 02:35:30', '2024-12-11 02:35:30'),
(27, 1, '2024-12-12 02:40:39', '2024-12-12 10:44:39', 'Hadir', 'Pulang Cepat', '2024-12-12 02:40:39', '2024-12-12 10:44:39'),
(28, 2, '2024-12-12 02:40:39', '2024-12-12 02:40:39', 'Alpha', '-', '2024-12-12 02:40:39', '2024-12-12 02:40:39'),
(29, 1, '2024-12-13 02:40:39', '2024-12-13 11:40:39', 'Hadir', 'Pulang', '2024-12-13 02:40:39', '2024-12-13 11:40:39'),
(30, 2, '2024-12-13 02:40:39', '2024-12-13 02:40:39', 'Alpha', '-', '2024-12-13 02:40:39', '2024-12-13 02:40:39'),
(49, 2, '2025-01-29 02:38:37', '2025-01-29 16:00:00', 'Hadir', 'Pulang', '2025-01-29 02:38:37', '2025-01-29 16:00:00'),
(51, 1, '2025-01-29 03:00:00', '2025-01-29 03:00:00', 'Alpha', '-', '2025-01-29 15:17:00', '2025-01-29 15:17:00');

-- --------------------------------------------------------

--
-- Table structure for table `data_pribadis`
--

CREATE TABLE `data_pribadis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `jabatan_organisasi_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `tempat_lahir` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_pribadis`
--

INSERT INTO `data_pribadis` (`id`, `user_id`, `jabatan_organisasi_id`, `tanggal_lahir`, `tempat_lahir`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '0001-01-01', '-', '2024-12-03 15:44:53', '2024-12-03 15:44:53'),
(2, 2, 1, '0001-01-01', 'Palembang', '2024-12-03 17:36:15', '2024-12-05 12:51:10');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jabatan_organisasis`
--

CREATE TABLE `jabatan_organisasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_jabatan` varchar(250) NOT NULL,
  `besaran_gaji` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jabatan_organisasis`
--

INSERT INTO `jabatan_organisasis` (`id`, `nama_jabatan`, `besaran_gaji`, `created_at`, `updated_at`) VALUES
(1, 'Kerajinan', 2500000, '2024-12-02 15:36:05', '2024-12-02 15:36:05'),
(2, 'Admin', 2500000, '2024-12-02 15:37:17', '2024-12-02 15:37:17'),
(3, 'Office boy', 1500000, '2024-12-02 15:38:06', '2024-12-02 15:38:06'),
(4, 'Keamanan', 1500000, '2024-12-02 15:39:34', '2024-12-02 15:39:34'),
(5, 'Chef', 2000000, '2024-12-02 15:39:58', '2024-12-02 16:31:04'),
(6, 'Marketing', 2500000, '2024-12-02 16:51:27', '2024-12-02 16:51:27');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_11_29_065636_create_pengaturan_absensis_table', 1),
(6, '2024_11_30_101641_create_absensis_table', 1),
(7, '2025_01_23_181046_jabatan_organisasi', 1),
(8, '2025_01_23_181340_data_pribadis', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_absensis`
--

CREATE TABLE `pengaturan_absensis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `waktu_buka` time(1) NOT NULL,
  `waktu_tutup` time(1) NOT NULL,
  `rentang_awal_IP` varchar(50) NOT NULL,
  `rentang_akhir_IP` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengaturan_absensis`
--

INSERT INTO `pengaturan_absensis` (`id`, `waktu_buka`, `waktu_tutup`, `rentang_awal_IP`, `rentang_akhir_IP`, `created_at`, `updated_at`) VALUES
(1, '09:30:00.0', '22:30:00.0', '127.0.0.1', '127.0.0.1', '2024-11-28 17:23:43', '2025-01-29 14:59:15');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'pegawai',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@material.com', '$2y$10$FLtRbpF3KMe4lYuA5AUgJ.SWV0491wuDD9yrkNFut2zFt6P6qbiga', 'admin', 'iHlUNcafdvclSJWLWqEN1TgeeN2ETi0zHkwjNbRDHVpi4VGVmngfFhaDUFlT', '2024-11-24 12:24:32', '2024-11-24 12:24:32'),
(2, 'Pegawai', 'pegawai@gmail.com', '$2y$10$59bxgekEIPdh4EIh3Qmqg.JbBG1Jl5vI1FldxXDbV8hX/xn4VNmbq', 'pegawai', NULL, '2024-12-03 17:36:15', '2025-01-24 03:51:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensis`
--
ALTER TABLE `absensis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absensis_user_id_foreign` (`user_id`);

--
-- Indexes for table `data_pribadis`
--
ALTER TABLE `data_pribadis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `data_pribadis_user_id_foreign` (`user_id`),
  ADD KEY `data_pribadis_jabatan_organisasi_id_foreign` (`jabatan_organisasi_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jabatan_organisasis`
--
ALTER TABLE `jabatan_organisasis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `pengaturan_absensis`
--
ALTER TABLE `pengaturan_absensis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensis`
--
ALTER TABLE `absensis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `data_pribadis`
--
ALTER TABLE `data_pribadis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jabatan_organisasis`
--
ALTER TABLE `jabatan_organisasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pengaturan_absensis`
--
ALTER TABLE `pengaturan_absensis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensis`
--
ALTER TABLE `absensis`
  ADD CONSTRAINT `absensis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `data_pribadis`
--
ALTER TABLE `data_pribadis`
  ADD CONSTRAINT `data_pribadis_jabatan_organisasi_id_foreign` FOREIGN KEY (`jabatan_organisasi_id`) REFERENCES `jabatan_organisasis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `data_pribadis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
