/*
 Navicat Premium Dump SQL

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 80043 (8.0.43)
 Source Host           : localhost:3306
 Source Schema         : _rahman

 Target Server Type    : MySQL
 Target Server Version : 80043 (8.0.43)
 File Encoding         : 65001

 Date: 06/02/2026 10:19:35
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for lokasi
-- ----------------------------
DROP TABLE IF EXISTS `lokasi`;
CREATE TABLE `lokasi` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `skpd_id` int DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `long` varchar(255) DEFAULT NULL,
  `radius` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of lokasi
-- ----------------------------
BEGIN;
INSERT INTO `lokasi` (`id`, `skpd_id`, `nama`, `lat`, `long`, `radius`, `created_at`, `updated_at`) VALUES (1, 1, 'kantor dinsos', '-3.328272', '114.588218', 1000, '2026-02-05 16:32:17', '2026-02-06 00:36:27');
INSERT INTO `lokasi` (`id`, `skpd_id`, `nama`, `lat`, `long`, `radius`, `created_at`, `updated_at`) VALUES (2, 1, 'sdf', '-3.321331', '114.579163', 1500, '2026-02-05 23:00:22', '2026-02-05 23:00:22');
COMMIT;

-- ----------------------------
-- Table structure for lokasi_pegawai
-- ----------------------------
DROP TABLE IF EXISTS `lokasi_pegawai`;
CREATE TABLE `lokasi_pegawai` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lokasi_id` int unsigned NOT NULL,
  `pegawai_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of lokasi_pegawai
-- ----------------------------
BEGIN;
INSERT INTO `lokasi_pegawai` (`id`, `lokasi_id`, `pegawai_id`, `created_at`, `updated_at`) VALUES (1, 1, 1, '2026-02-05 16:32:33', '2026-02-05 16:32:33');
INSERT INTO `lokasi_pegawai` (`id`, `lokasi_id`, `pegawai_id`, `created_at`, `updated_at`) VALUES (2, 1, 2, '2026-02-05 17:28:08', '2026-02-05 17:28:08');
INSERT INTO `lokasi_pegawai` (`id`, `lokasi_id`, `pegawai_id`, `created_at`, `updated_at`) VALUES (3, 2, 1, '2026-02-05 23:00:32', '2026-02-05 23:00:32');
COMMIT;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
BEGIN;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4, '2025_11_03_033906_add_role_to_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5, '2025_11_03_033943_create_pegawai_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6, '2025_11_03_035510_add_username_to_users_table', 1);
COMMIT;

-- ----------------------------
-- Table structure for pegawai
-- ----------------------------
DROP TABLE IF EXISTS `pegawai`;
CREATE TABLE `pegawai` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `jkel` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `skpd_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of pegawai
-- ----------------------------
BEGIN;
INSERT INTO `pegawai` (`id`, `nama`, `tgl_lahir`, `jkel`, `nik`, `telp`, `alamat`, `created_at`, `updated_at`, `user_id`, `skpd_id`) VALUES (1, 'andi law', '1990-02-05', 'L', '6371030807720012', '0987656789', '-', '2026-02-05 16:31:11', '2026-02-05 16:56:27', 2, 1);
INSERT INTO `pegawai` (`id`, `nama`, `tgl_lahir`, `jkel`, `nik`, `telp`, `alamat`, `created_at`, `updated_at`, `user_id`, `skpd_id`) VALUES (2, 'dfgfdg', '2026-02-19', 'L', '2345665654367889', '098765', '-', '2026-02-05 17:15:49', '2026-02-05 17:43:38', 4, 1);
COMMIT;

-- ----------------------------
-- Table structure for presensi
-- ----------------------------
DROP TABLE IF EXISTS `presensi`;
CREATE TABLE `presensi` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_datang` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `lokasi_id` int unsigned NOT NULL,
  `skpd_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`lokasi_id`,`skpd_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of presensi
-- ----------------------------
BEGIN;
INSERT INTO `presensi` (`id`, `nik`, `nama`, `tanggal`, `jam_datang`, `jam_pulang`, `lokasi_id`, `skpd_id`, `created_at`, `updated_at`) VALUES (1, '6371030807720012', 'andi', '2026-02-06', '06:28:26', '10:17:19', 1, 1, NULL, '2026-02-06 02:17:19');
COMMIT;

-- ----------------------------
-- Table structure for skpd
-- ----------------------------
DROP TABLE IF EXISTS `skpd`;
CREATE TABLE `skpd` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `kode` varchar(255) DEFAULT NULL,
  `user_id` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of skpd
-- ----------------------------
BEGIN;
INSERT INTO `skpd` (`id`, `nama`, `kode`, `user_id`, `created_at`, `updated_at`) VALUES (1, 'Dinas Sosial', '001', 3, '2026-02-05 16:29:42', '2026-02-05 16:53:55');
INSERT INTO `skpd` (`id`, `nama`, `kode`, `user_id`, `created_at`, `updated_at`) VALUES (2, 'Dinas Pendidikan', '002', NULL, '2026-02-05 16:29:50', '2026-02-05 16:29:50');
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES (1, 'superadmin', 'admin', NULL, NULL, '$2y$10$KZYOxE8KRu8zQNgc2o/b/.mOcHCX6iD2KtFmanVDcPdpFhDdDYf/.', 'admin', NULL, NULL, NULL);
INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES (2, 'andi law', '6371030807720012', NULL, NULL, '$2y$12$UJkfZFolneveAAloI/W4iuN9tgSqRRblQyyiRpvtSTvPMIcOYkMsG', 'pegawai', NULL, '2026-02-05 16:31:16', '2026-02-05 16:32:53');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES (3, 'Dinas Sosial', '001', NULL, NULL, '$2y$12$yuF77bc1CQRjWeSkJ/ZIvuCWz0eDzyp2gmw1nI95/ox5TJnfAD99y', 'skpd', NULL, '2026-02-05 16:53:55', '2026-02-05 16:53:58');
INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES (4, 'dfgfdg', '2345665654367889', NULL, NULL, '$2y$12$pKeL5D7ocFJIi9TIzpASteeJ6hbZ85vKpDIWaW2rS7cPav2Y4RMK.', 'pegawai', NULL, '2026-02-05 17:43:38', '2026-02-05 17:43:43');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
