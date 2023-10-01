/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : 127.0.0.1:3306
 Source Schema         : saka

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 01/10/2023 14:52:51
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for akun
-- ----------------------------
DROP TABLE IF EXISTS `akun`;
CREATE TABLE `akun`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_akun` varchar(255) CHARACTER SET ascii COLLATE ascii_bin NULL DEFAULT NULL,
  `nama_akun` varchar(255) CHARACTER SET ascii COLLATE ascii_bin NULL DEFAULT NULL,
  `jenis_akun_id` int NULL DEFAULT NULL COMMENT '1 = debit | 2 = kredit',
  `jumlah` varchar(255) CHARACTER SET ascii COLLATE ascii_bin NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = ascii COLLATE = ascii_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of akun
-- ----------------------------
INSERT INTO `akun` VALUES (1, '111', 'Kas Apotek Saka Sasmitra', 1, '311000', '2023-05-03 23:34:16', 1, '2023-09-29 22:10:58', 1);
INSERT INTO `akun` VALUES (2, '211', 'Hutang Dagang', 2, '0', '2023-05-03 23:34:16', 1, '2023-05-14 09:24:17', 1);
INSERT INTO `akun` VALUES (3, '611', 'Biaya Listrik', 2, '14000', '2023-05-03 23:34:16', 1, '2023-09-29 20:52:30', 1);
INSERT INTO `akun` VALUES (4, '622', 'Biaya Gaji', 2, '0', '2023-05-03 23:34:16', 1, '2023-05-03 23:34:16', 1);
INSERT INTO `akun` VALUES (5, '911', 'Beban Sewa', 2, '0', '2023-05-03 23:34:16', 1, '2023-05-03 23:34:16', 1);
INSERT INTO `akun` VALUES (6, '612', 'Biaya air', 2, '5000', '2023-05-03 23:34:16', 1, '2023-09-28 23:00:13', 1);
INSERT INTO `akun` VALUES (7, '311', 'Modal Tn Wasisi', 2, '2531000', '2023-05-03 23:34:16', 1, '2023-09-28 17:38:58', 1);
INSERT INTO `akun` VALUES (8, '411', 'Penjualan Barang', 2, '502750', '2023-05-03 23:34:16', 1, '2023-09-29 22:10:58', 1);
INSERT INTO `akun` VALUES (9, '114', 'Pembelian', 1, '0', '2023-05-03 23:34:16', 1, '2023-09-19 05:05:14', 1);
INSERT INTO `akun` VALUES (10, '113', 'Persediaan Barang Dagang', 1, '2193500', '2023-05-03 23:34:16', 1, '2023-09-29 22:10:58', 1);
INSERT INTO `akun` VALUES (11, '312', 'Prive Tn. Wasis', 1, '0', '2023-05-03 23:34:16', 1, '2023-05-03 23:34:16', 1);
INSERT INTO `akun` VALUES (12, '511', 'Retur Pembelian', 1, '0', '2023-05-03 23:34:16', 1, '2023-05-03 23:34:16', 1);
INSERT INTO `akun` VALUES (13, '118', 'Peralatan Toko', 1, '0', '2023-05-03 23:34:16', 1, '2023-05-03 23:34:16', 1);
INSERT INTO `akun` VALUES (14, '512', 'Retur Penjualan', 1, '0', '2023-05-03 23:34:16', 1, '2023-05-03 23:34:16', 1);
INSERT INTO `akun` VALUES (15, '422', 'Potongan Penjualan', 2, '0', '2023-05-03 23:34:16', 1, '2023-05-03 23:34:16', 1);
INSERT INTO `akun` VALUES (16, '115', 'Penyusutan Peralatan Kantor', 1, '0', '2023-05-03 23:34:16', 1, '2023-05-03 23:34:16', 1);
INSERT INTO `akun` VALUES (17, '116', 'Penyusutan Gedung', 1, '0', '2023-05-03 23:34:16', 1, '2023-05-03 23:34:16', 1);
INSERT INTO `akun` VALUES (18, '117', 'Pajak Penghasilan', 1, '0', '2023-05-03 23:34:16', 1, '2023-05-03 23:34:16', 1);
INSERT INTO `akun` VALUES (19, '423', 'Potongan Pembelian', 1, '0', '2023-05-03 23:34:16', 1, '2023-05-03 23:34:16', 1);
INSERT INTO `akun` VALUES (32, '119', 'HPP', 1, '487500', '2023-06-13 04:17:21', 1, '2023-09-29 22:10:58', 1);
INSERT INTO `akun` VALUES (34, '112', 'Kas Bank', 1, '22750', '2023-09-19 04:42:17', 1, '2023-09-22 11:33:41', 1);

-- ----------------------------
-- Table structure for barang
-- ----------------------------
DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `satuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `jenis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_batch` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ed` date NOT NULL,
  `harga_beli_grosir` int NULL DEFAULT NULL,
  `harga_jual_grosir` int NULL DEFAULT NULL,
  `satuan_grosir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `jumlah_grosir` int NULL DEFAULT NULL,
  `stok_grosir` int NULL DEFAULT NULL,
  `harga_beli` int NULL DEFAULT NULL,
  `harga_jual` int NULL DEFAULT NULL,
  `stok` int NULL DEFAULT 0,
  `harga_jual_tablet` int NULL DEFAULT NULL,
  `stok_minim` int NULL DEFAULT NULL,
  `sisa_pecahan` int NULL DEFAULT 0,
  `jumlah_pecahan` int NULL DEFAULT 0,
  `is_delete` int NULL DEFAULT 0,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 139 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of barang
-- ----------------------------
INSERT INTO `barang` VALUES (1, 'AMBEVEN', 'pcs', 'Generik', 'KDJSA03413', '2024-06-06', 25000, 27000, 'BOX', 5, 1, 5000, 6000, 6, NULL, 0, 0, 0, 0, 1, NULL, 1, '2023-09-19 05:10:07');
INSERT INTO `barang` VALUES (2, 'ANTANGIN', 'pcs', 'Generik', '0', '2027-11-24', 28000, 27000, 'BOX', 4, 3, 1500, 4550, 14, NULL, 0, 0, 0, 0, 1, NULL, 1, '2023-09-22 11:33:41');
INSERT INTO `barang` VALUES (3, 'ANTANGIN JRG TABLET', 'pcs', 'Generik', '0', '2023-04-20', 28000, 25000, NULL, NULL, NULL, 300, 390, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (4, 'BYE BYE FEVER', 'pcs', 'Generik', '0', '2023-04-21', NULL, NULL, NULL, NULL, NULL, 10000, 13000, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (5, 'BASLEM LANG', 'pcs', 'Generik', '0', '2023-04-22', NULL, NULL, NULL, NULL, NULL, 12000, 15600, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (6, 'BETADIN', 'pcs', 'Generik', '0', '2023-04-23', NULL, NULL, NULL, NULL, NULL, 6000, 7800, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (7, 'BODREX', 'pcs', 'Generik', '0', '2023-04-24', NULL, NULL, NULL, NULL, NULL, 10000, 13000, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (8, 'BODREX EXTRA', 'pcs', 'Generik', '0', '2023-04-25', NULL, NULL, NULL, NULL, NULL, 2500, 3250, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (9, 'BODREX FLU DAN BATUK', 'pcs', 'Generik', '0', '2023-04-26', NULL, NULL, NULL, NULL, NULL, 3000, 3900, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (10, 'BODREX MIGREN', 'pcs', 'Generik', '0', '2023-04-27', NULL, NULL, NULL, NULL, NULL, 2500, 3250, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (11, 'CALADIN 60 GRM', 'pcs', 'Generik', '12', '2023-05-30', NULL, NULL, NULL, NULL, NULL, 25000, 52500, 0, NULL, 0, 0, 0, 0, 1, NULL, 1, '2023-05-10 10:24:31');
INSERT INTO `barang` VALUES (12, 'CALADIN 100 GRM', 'pcs', 'Generik', '0', '2023-04-29', NULL, NULL, NULL, NULL, NULL, 17500, 22750, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (13, 'CAP KAMPAK 3ML', 'pcs', 'Generik', '0', '2023-04-30', NULL, NULL, NULL, NULL, NULL, 8000, 10400, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (14, 'CAP KAMPAK 5ML', 'pcs', 'Generik', '0', '2023-05-01', NULL, NULL, NULL, NULL, NULL, 10000, 13000, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (15, 'CAP KAMPAK 10 ML', 'pcs', 'Generik', '0', '2023-05-02', NULL, NULL, NULL, NULL, NULL, 15000, 19500, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (16, 'CAP KAMPAK 14 ML', 'pcs', 'Generik', '0', '2023-05-03', NULL, NULL, NULL, NULL, NULL, 21000, 27300, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (17, 'CAP KAMPAK 56 ML', 'pcs', 'Generik', '0', '2023-05-04', NULL, NULL, NULL, NULL, NULL, 62000, 80600, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (18, 'CURCUMA PLUS', 'pcs', 'Generik', '0', '2023-05-05', NULL, NULL, NULL, NULL, NULL, 22000, 28600, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (19, 'DECOLGEN', 'pcs', 'Generik', '0', '2023-05-06', NULL, NULL, NULL, NULL, NULL, 2000, 2600, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (20, 'DRAGON OBAT GOSOK 5GRM', 'pcs', 'Generik', '0', '2023-05-07', NULL, NULL, NULL, NULL, NULL, 13000, 16900, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (21, 'DRAGON OBAT GOSOK BESAR', 'pcs', 'Generik', '0', '2023-05-08', NULL, NULL, NULL, NULL, NULL, 35000, 45500, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (22, 'ENERVON C', 'pcs', 'Generik', '0', '2023-05-09', NULL, NULL, NULL, NULL, NULL, 5500, 7150, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (23, 'ENTROSTOP', 'pcs', 'Generik', '0', '2023-05-10', NULL, NULL, NULL, NULL, NULL, 7000, 9100, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (24, 'FRESCARE MINYAK KAYU PUTIH', 'pcs', 'Generik', '0', '2023-05-11', NULL, NULL, NULL, NULL, NULL, 12000, 15600, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (25, 'FRESCARE HIJAU', 'pcs', 'Generik', '0', '2023-05-12', NULL, NULL, NULL, NULL, NULL, 12000, 15600, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (26, 'FRESCARE KUNING', 'pcs', 'Generik', '0', '2023-05-13', NULL, NULL, NULL, NULL, NULL, 12000, 15600, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (27, 'FRESCARE UNGU', 'pcs', 'Generik', '0', '2023-05-14', NULL, NULL, NULL, NULL, NULL, 12000, 15600, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (28, 'GELIGA BALSEM', 'pcs', 'Generik', '0', '2023-05-15', NULL, NULL, NULL, NULL, NULL, 6000, 7800, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (29, 'GPU 30 ML', 'pcs', 'Generik', '0', '2023-05-16', NULL, NULL, NULL, NULL, NULL, 10000, 13000, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (30, 'GPU 60 ML', 'pcs', 'Generik', '0', '2023-05-17', NULL, NULL, NULL, NULL, NULL, 17000, 22100, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (31, 'HUFAGRIP', 'pcs', 'Generik', '0', '2023-05-18', NULL, NULL, NULL, NULL, NULL, 20000, 26000, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (32, 'HEROCYN 50 MG', 'pcs', 'Generik', '0', '2023-05-19', NULL, NULL, NULL, NULL, NULL, 8000, 10400, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (33, 'HEROCYN 85 MG', 'pcs', 'Generik', '0', '2023-05-20', NULL, NULL, NULL, NULL, NULL, 12500, 16250, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (34, 'HOT CREAM 60 MG', 'pcs', 'Generik', '0', '2023-05-21', NULL, NULL, NULL, NULL, NULL, 18000, 23400, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (35, 'HOT CREAM 120 MG', 'pcs', 'Generik', '0', '2023-05-22', NULL, NULL, NULL, NULL, NULL, 26000, 33800, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (36, 'HANSAPLAS KECIL', 'pcs', 'Generik', '0', '2023-05-23', NULL, NULL, NULL, NULL, NULL, 500, 650, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (37, 'HANSAPLAS BESAR', 'pcs', 'Generik', '0', '2023-05-24', NULL, NULL, NULL, NULL, NULL, 1000, 1300, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (38, 'INSTO TETES MATA', 'pcs', 'Generik', '0', '2023-05-25', NULL, NULL, NULL, NULL, NULL, 15000, 19500, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (39, 'KOYO CABE', 'pcs', 'Generik', '0', '2023-05-26', NULL, NULL, NULL, NULL, NULL, 10000, 13000, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (40, 'LISTERIN BESAR', 'pcs', 'Generik', '0', '2023-05-27', NULL, NULL, NULL, NULL, NULL, 30000, 39000, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (41, 'LISTERIN KECIL', 'pcs', 'Generik', '0', '2023-05-28', NULL, NULL, NULL, NULL, NULL, 10000, 13000, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (42, 'MIXAGRIP', 'pcs', 'Generik', '0', '2023-05-29', NULL, NULL, NULL, NULL, NULL, 2500, 3250, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (43, 'MINYAK KAYU PUTIH 30ML', 'pcs', 'Generik', '0', '2023-05-30', NULL, NULL, NULL, NULL, NULL, 19000, 24700, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (44, 'MINYAK KAYU PUTIH 60 ML', 'pcs', 'Generik', '0', '2023-05-31', NULL, NULL, NULL, NULL, NULL, 23000, 29900, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (45, 'MINYAK KAYU PUTIH 120 ML', 'pcs', 'Generik', '0', '2023-06-01', NULL, NULL, NULL, NULL, NULL, 40500, 52650, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (46, 'MINYAK KAYU PUTIH 210 ML', 'pcs', 'Generik', '0', '2023-06-02', NULL, NULL, NULL, NULL, NULL, 69000, 89700, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (47, 'MINYAK KAYU PUTIH DRAGON 15ML', 'pcs', 'Generik', '0', '2023-06-03', NULL, NULL, NULL, NULL, NULL, 6500, 8450, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (48, 'MINYAK KAYU PUTIH DRAGON 30 ML', 'pcs', 'Generik', '0', '2023-06-04', NULL, NULL, NULL, NULL, NULL, 10500, 13650, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (49, 'MINYAK KAYU PUTIH DRAGON 60 ML', 'pcs', 'Generik', '0', '2023-06-05', NULL, NULL, NULL, NULL, NULL, 19000, 24700, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (50, 'MINYAK KAYU PUTIH GAJAH 15 ML', 'pcs', 'Generik', '0', '2023-06-06', NULL, NULL, NULL, NULL, NULL, 7000, 9100, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (51, 'MINYAK KAYU PUTIH GAJAH 30 ML', 'pcs', 'Generik', '0', '2023-06-07', NULL, NULL, NULL, NULL, NULL, 11000, 14300, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (52, 'MY BABY 60 ML', 'pcs', 'Generik', '0', '2023-06-08', NULL, NULL, NULL, NULL, NULL, 17000, 22100, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (53, 'MY BABY 90 ML', 'pcs', 'Generik', '0', '2023-06-09', NULL, NULL, NULL, NULL, NULL, 23000, 29900, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (54, 'MYLANTA', 'pcs', 'Generik', '0', '2023-06-10', NULL, NULL, NULL, NULL, NULL, 14500, 18850, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (55, 'NEUROBION FORTE', 'pcs', 'Generik', '0', '2023-06-11', NULL, NULL, NULL, NULL, NULL, 3700, 4810, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (56, 'NEUROBIN VIT B1,B6,B12', 'pcs', 'Generik', '0', '2023-06-12', NULL, NULL, NULL, NULL, NULL, 2200, 2860, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (57, 'OSKADON', 'pcs', 'Generik', '0', '2023-06-13', NULL, NULL, NULL, NULL, NULL, 2500, 3250, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (58, 'PANADOL MERAH', 'pcs', 'Generik', '0', '2023-06-14', NULL, NULL, NULL, NULL, NULL, 12000, 15600, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (59, 'PARAMEX NYERI OTOT', 'pcs', 'Generik', '0', '2023-06-15', NULL, NULL, NULL, NULL, NULL, 3000, 3900, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (60, 'PARAMEX ', 'pcs', 'Generik', '0', '2023-06-16', NULL, NULL, NULL, NULL, NULL, 2500, 3250, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (61, 'PROMAG', 'pcs', 'Generik', '0', '2023-06-17', NULL, NULL, NULL, NULL, NULL, 7500, 9750, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (62, 'PROTECAL SOLID', 'pcs', 'Generik', '0', '2023-06-18', NULL, NULL, NULL, NULL, NULL, 4000, 5200, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (63, 'PROTECAL DEFENSE', 'pcs', 'Generik', '0', '2023-06-19', NULL, NULL, NULL, NULL, NULL, 4000, 5200, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (64, 'RHEUMACYL', 'pcs', 'Generik', '0', '2023-06-20', NULL, NULL, NULL, NULL, NULL, 10000, 13000, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (65, 'SANMOL SYRUP', 'pcs', 'Generik', '0', '2023-06-21', NULL, NULL, NULL, NULL, NULL, 16000, 20800, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (66, 'SANGOBION ', 'pcs', 'Generik', '0', '2023-06-22', NULL, NULL, NULL, NULL, NULL, 6500, 8450, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (67, 'SALANPAS', 'pcs', 'Generik', '0', '2023-06-23', NULL, NULL, NULL, NULL, NULL, 8000, 10400, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (68, 'STIMUNO SYRUP', 'pcs', 'Generik', '0', '2023-06-24', NULL, NULL, NULL, NULL, NULL, 30000, 39000, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (69, 'TOlAK ANGIN', 'pcs', 'Generik', '0', '2023-06-25', NULL, NULL, NULL, NULL, NULL, 3500, 4550, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (70, 'TOLAK ANGIN FLU', 'pcs', 'Generik', '0', '2023-06-26', NULL, NULL, NULL, NULL, NULL, 4000, 5200, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (71, 'TELONG LANG 30 ML', 'pcs', 'Generik', '0', '2023-06-27', NULL, NULL, NULL, NULL, NULL, 10000, 13000, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (72, 'TELONG LANG 60 ML', 'pcs', 'Generik', '0', '2023-06-28', NULL, NULL, NULL, NULL, NULL, 18000, 23400, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (73, 'ULTRAFLU', 'pcs', 'Generik', '0', '2023-06-29', NULL, NULL, NULL, NULL, NULL, 3500, 4550, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (74, 'VIT B COMP', 'pcs', 'Generik', '0', '2023-06-30', NULL, NULL, NULL, NULL, NULL, 6000, 7800, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (75, 'VIT C IPI', 'pcs', 'Generik', '0', '2023-07-01', NULL, NULL, NULL, NULL, NULL, 6000, 7800, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (76, 'VIT C 1000', 'pcs', 'Generik', '0', '2023-07-02', NULL, NULL, NULL, NULL, NULL, 8000, 10400, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (77, 'VIK INHELER', 'pcs', 'Generik', '0', '2023-07-03', NULL, NULL, NULL, NULL, NULL, 18000, 23400, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (78, 'XONCE', 'pcs', 'Generik', '0', '2023-07-04', NULL, NULL, NULL, NULL, NULL, 3000, 3900, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (79, 'STRIP SALAP', 'pcs', 'Generik', '0', '2023-07-05', NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (80, 'MADURASA', 'pcs', 'Generik', '0', '2023-07-06', NULL, NULL, NULL, NULL, NULL, 1000, 1300, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (81, 'TELONG LANG 10 JAM 30 ML', 'pcs', 'Generik', '0', '2023-07-07', NULL, NULL, NULL, NULL, NULL, 12000, 15600, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (82, 'TELONG LANG  10 JAM 60 ML', 'pcs', 'Generik', '0', '2023-07-08', NULL, NULL, NULL, NULL, NULL, 20000, 26000, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (83, 'ORALIT', 'pcs', 'Generik', '0', '2023-07-09', NULL, NULL, NULL, NULL, NULL, 3000, 3900, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (84, 'PEMBALUT CHAMP', 'pcs', 'Generik', '0', '2023-07-10', NULL, NULL, NULL, NULL, NULL, 7000, 9100, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (85, 'SABUN ASEPSO ', 'pcs', 'Generik', '0', '2023-07-11', NULL, NULL, NULL, NULL, NULL, 9000, 11700, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (86, 'DETOL BIRU', 'pcs', 'Generik', '0', '2023-07-12', NULL, NULL, NULL, NULL, NULL, 7000, 9100, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (87, 'DETOL ORANGE', 'pcs', 'Generik', '0', '2023-07-13', NULL, NULL, NULL, NULL, NULL, 7000, 9100, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (88, 'KAPAS CINDERELA', 'pcs', 'Generik', '0', '2023-07-14', NULL, NULL, NULL, NULL, NULL, 4000, 5200, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (89, 'POWDER MBK', 'pcs', 'Generik', '0', '2023-07-15', NULL, NULL, NULL, NULL, NULL, 3000, 3900, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (90, 'POPOK CELANA LIFREE 4 PCS', 'pcs', 'Generik', '0', '2023-07-16', NULL, NULL, NULL, NULL, NULL, 25000, 32500, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (91, 'POPOK CELANA LIFREE 1 PCS M', 'pcs', 'Generik', '0', '2023-07-17', NULL, NULL, NULL, NULL, NULL, 8000, 10400, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (92, 'POPOK CELANA LIFREE 1 PCS XL', 'pcs', 'Generik', '0', '2023-07-18', NULL, NULL, NULL, NULL, NULL, 10000, 13000, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (93, 'ALKOHOL 70 %', 'pcs', 'Generik', 'SASDFI213S5', '2024-04-23', 0, NULL, NULL, 0, 0, 3333, 10400, 0, NULL, 0, 0, 0, 0, 1, NULL, 1, '2023-06-02 20:09:20');
INSERT INTO `barang` VALUES (94, 'CATTON BUD SELETION', 'pcs', 'Generik', '0', '2023-07-20', NULL, NULL, NULL, NULL, NULL, 6000, 7800, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (95, 'CATTON BUD HUKI', 'pcs', 'Generik', '0', '2023-07-21', NULL, NULL, NULL, NULL, NULL, 12000, 15600, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (96, 'CATTON BUD MULTI BUNDDS', 'pcs', 'Generik', '0', '2023-07-22', NULL, NULL, NULL, NULL, NULL, 2000, 2600, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (97, 'TOLAK ANGIN PERMEN', 'pcs', 'Generik', '0', '2023-07-23', NULL, NULL, NULL, NULL, NULL, 2500, 3250, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (98, 'ANTANGIN  PERMEN', 'pcs', 'Generik', 'SASDFI213S5', '2023-05-31', NULL, NULL, NULL, NULL, NULL, 5000, 2600, 0, NULL, 0, 0, 0, 0, 1, NULL, 1, '2023-05-16 21:31:46');
INSERT INTO `barang` VALUES (99, 'POLYSILAN SYRUP', 'pcs', 'Generik', '0', '2023-07-25', NULL, NULL, NULL, NULL, NULL, 23000, 29900, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (100, 'VIK VAPORUB', 'pcs', 'Generik', '0', '2023-07-26', NULL, NULL, NULL, NULL, NULL, 8000, 10400, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (101, 'BEDAK SALISIL GAJAH MENTOL', 'pcs', 'Generik', '0', '2023-07-27', NULL, NULL, NULL, NULL, NULL, 7000, 9100, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (102, 'BEDAK SALISIL GAJAH NON MENTOL', 'pcs', 'Generik', '0', '2023-07-28', NULL, NULL, NULL, NULL, NULL, 9000, 11700, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (103, 'MINYAK TAWON BESAR', 'pcs', 'Generik', '0', '2023-07-29', NULL, NULL, NULL, NULL, NULL, 48000, 62400, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (104, 'MINYAK TAWON KECIL', 'pcs', 'Generik', '0', '2023-07-30', NULL, NULL, NULL, NULL, NULL, 30000, 39000, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (105, 'SCRUBBER', 'pcs', 'Generik', '0', '2023-07-31', NULL, NULL, NULL, NULL, NULL, 2500, 3250, 0, NULL, 0, 0, 0, 0, 1, NULL, NULL, NULL);
INSERT INTO `barang` VALUES (106, 'ADEM SARI', 'Tablet', 'Generik', 'SASDFI213S5', '2024-11-16', 25000, 27000, 'BOX', 0, 0, 5000, 7500, 0, NULL, 0, 0, 0, 0, 1, NULL, 1, '2023-09-27 00:45:44');
INSERT INTO `barang` VALUES (107, 'BYSOL', 'pcs', 'Generik', '0', '2023-08-02', NULL, NULL, NULL, NULL, NULL, 23000, 29900, 0, NULL, 0, 0, 0, 0, 1, NULL, 1, '2023-05-20 17:46:03');
INSERT INTO `barang` VALUES (118, 'ADEM SARI', 'pcs', 'Generik', 'aaaaaa', '2023-05-17', NULL, NULL, NULL, NULL, NULL, 25000, 5000, 0, NULL, 0, 0, 0, 1, 1, '2023-05-29 18:56:06', 1, '2023-05-29 18:58:27');
INSERT INTO `barang` VALUES (119, 'ADEM SARI', 'pcs', 'Generik', 'BKSPTES', '2024-06-13', NULL, NULL, NULL, NULL, NULL, 25000, 5000, 0, NULL, 0, 0, 0, 0, 1, '2023-05-29 18:57:33', 2, '2023-05-29 18:57:33');
INSERT INTO `barang` VALUES (121, 'barang contoh 2', 'pcs', 'Salep', 'FBK010101', '2024-03-09', 0, 0, 'BOX', 5, 3, 2000, 2500, 19, NULL, 0, 4, 6, 0, 1, '2023-05-29 19:17:59', 1, '2023-09-19 19:22:02');
INSERT INTO `barang` VALUES (122, 'barang contoh 1', 'pcs', 'Salep', 'wawaawaw', '2023-10-13', 0, 0, 'BOX', 0, 0, 2000, 2500, 0, NULL, 0, 0, 0, 0, 1, '2023-05-29 19:57:31', 1, '2023-06-02 16:12:19');
INSERT INTO `barang` VALUES (128, 'ADEM SARI', 'pcs', 'Generik', 'adm001', '2023-07-31', 25000, 27000, 'BOX', 5, 1, 5000, 6500, 5, NULL, 0, 0, 0, 0, 1, '2023-07-13 00:28:18', 1, '2023-07-13 00:28:18');
INSERT INTO `barang` VALUES (130, 'barang testing 1212', 'pcs', 'Generik', 'Tester123', '2024-06-21', 55000, NULL, 'BOX', 3, 5, 18333, NULL, 15, NULL, 0, 2, 6, 0, 1, '2023-08-24 02:35:54', 1, '2023-09-19 19:22:02');
INSERT INTO `barang` VALUES (134, 'barang manual', 'pcs', 'Paten', 'BRGMNL011', '2024-03-20', NULL, NULL, 'BOX', 6, 4, 30000, NULL, 28, NULL, 2, 0, 12, 0, 1, '2023-09-19 15:41:01', NULL, '2023-09-19 15:41:01');
INSERT INTO `barang` VALUES (135, 'testingWii', 'pcs', 'Alkes', 'MAN0202', '2024-02-21', 45000, NULL, 'BOX', 6, 5, 12000, NULL, 30, NULL, 2, 0, 12, 0, 1, '2023-09-19 15:45:35', NULL, '2023-09-19 15:45:35');
INSERT INTO `barang` VALUES (136, 'Barang 01', 'Tablet', 'Alkes', 'CUST001', '2023-10-19', 25000, 30000, 'BOX', 12, 1, 12000, 15000, 15, NULL, 1, 0, 6, 1, 1, '2023-09-27 00:48:28', 1, '2023-09-28 16:17:09');
INSERT INTO `barang` VALUES (137, 'ContohBarangCustom', 'pcs', 'Alkes', 'CUST004', '2024-11-28', 325000, 400000, 'BOX', 12, 1, 27083, 30000, 21, 4000, 1, 3, 6, 0, 1, '2023-09-28 16:17:03', 1, '2023-09-29 22:10:58');
INSERT INTO `barang` VALUES (138, 'ContohBarangCustom2', 'pcs', 'Generik', 'CUST006', '2024-05-02', 48000, 50000, 'BOX', 12, 9, 12000, 15000, 119, 3000, 1, 0, 5, 0, 1, '2023-09-28 17:38:58', 1, '2023-09-29 22:10:58');

-- ----------------------------
-- Table structure for biaya
-- ----------------------------
DROP TABLE IF EXISTS `biaya`;
CREATE TABLE `biaya`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_akun` int NOT NULL,
  `tanggal` date NULL DEFAULT NULL,
  `jumlah` int NOT NULL,
  `status` int NOT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of biaya
-- ----------------------------
INSERT INTO `biaya` VALUES (15, 611, '2023-09-28', 10000, 2, 1, '2023-09-28 22:58:51', NULL, '2023-09-28 22:58:51');
INSERT INTO `biaya` VALUES (16, 612, '2023-09-28', 5000, 2, 1, '2023-09-28 23:00:13', NULL, '2023-09-28 23:00:13');
INSERT INTO `biaya` VALUES (17, 611, '2023-09-29', 2000, 2, 1, '2023-09-29 20:50:52', NULL, '2023-09-29 20:50:52');
INSERT INTO `biaya` VALUES (18, 611, '2023-09-29', 2000, 2, 1, '2023-09-29 20:52:30', NULL, '2023-09-29 20:52:30');

-- ----------------------------
-- Table structure for dokter
-- ----------------------------
DROP TABLE IF EXISTS `dokter`;
CREATE TABLE `dokter`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_dokter` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_telp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `kota` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of dokter
-- ----------------------------
INSERT INTO `dokter` VALUES (3, 'dokter1', '08511231442', 'Surabaya', 'Surabaya', 1, '2023-05-08 11:17:22', NULL, '2023-05-08 11:17:22');

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for history_pasien
-- ----------------------------
DROP TABLE IF EXISTS `history_pasien`;
CREATE TABLE `history_pasien`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pasien` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `tanggal` date NOT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  INDEX `id`(`id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of history_pasien
-- ----------------------------
INSERT INTO `history_pasien` VALUES (29, 3, 442, '2023-09-22', '2023-09-22 11:33:41', '2023-09-22 11:33:41');
INSERT INTO `history_pasien` VALUES (30, 1, 446, '2023-09-28', '2023-09-28 18:16:49', '2023-09-28 18:16:49');
INSERT INTO `history_pasien` VALUES (31, 1, 460, '2023-09-29', '2023-09-29 22:10:58', '2023-09-29 22:10:58');

-- ----------------------------
-- Table structure for jenis_akun
-- ----------------------------
DROP TABLE IF EXISTS `jenis_akun`;
CREATE TABLE `jenis_akun`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `jenis` varchar(255) CHARACTER SET ascii COLLATE ascii_bin NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = ascii COLLATE = ascii_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jenis_akun
-- ----------------------------
INSERT INTO `jenis_akun` VALUES (1, 'Debit');
INSERT INTO `jenis_akun` VALUES (2, 'Kredit');

-- ----------------------------
-- Table structure for keranjang
-- ----------------------------
DROP TABLE IF EXISTS `keranjang`;
CREATE TABLE `keranjang`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_barang` int NOT NULL,
  `type` int NOT NULL COMMENT '1 = Pembelian | 2 = Penjualan| 3 = Obat Racik',
  `created_by` int NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_barang_foreign`(`id_barang` ASC) USING BTREE,
  CONSTRAINT `id_barang_foreign` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 41 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of keranjang
-- ----------------------------

-- ----------------------------
-- Table structure for log
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NULL DEFAULT NULL,
  `nomor_transaksi` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `waktu` datetime NULL DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `jumlah` int NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 129 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of log
-- ----------------------------
INSERT INTO `log` VALUES (119, 1, 'PJL120230919', '2023-09-19 05:01:36', 'Penjualan Barang Dari Transaksi PJL120230919', 10000, 1, '2023-09-19 05:01:36', NULL, '2023-09-19 05:01:36');
INSERT INTO `log` VALUES (120, 1, 'PJL220230919', '2023-09-19 05:03:22', 'Penjualan Barang Dari Transaksi PJL220230919', 24000, 1, '2023-09-19 05:03:22', NULL, '2023-09-19 05:03:22');
INSERT INTO `log` VALUES (121, 1, 'PJL320230919', '2023-09-19 05:05:15', 'Penjualan Barang Dari Transaksi PJL320230919', 6000, 1, '2023-09-19 05:05:15', NULL, '2023-09-19 05:05:15');
INSERT INTO `log` VALUES (122, 1, 'PJL420230919', '2023-09-19 05:10:07', 'Penjualan Barang Dari Transaksi PJL420230919', 6000, 1, '2023-09-19 05:10:07', NULL, '2023-09-19 05:10:07');
INSERT INTO `log` VALUES (123, 1, 'PJL320230919', '2023-09-19 19:22:02', 'Penjualan Obat Racik Dari Transaksi PJL320230919', 30000, 1, '2023-09-19 19:22:02', NULL, '2023-09-19 19:22:02');
INSERT INTO `log` VALUES (124, 1, '', '2023-09-19 19:45:05', 'Penambahan Modal Sebanyak 500000', 500000, 1, '2023-09-19 19:45:05', NULL, '2023-09-19 19:45:05');
INSERT INTO `log` VALUES (125, 1, 'PJL120230922', '2023-09-22 11:33:42', 'Penjualan Barang Dari Transaksi PJL120230922', 22750, 1, '2023-09-22 11:33:42', NULL, '2023-09-22 11:33:42');
INSERT INTO `log` VALUES (126, 1, 'PJL220230928', '2023-09-28 18:16:49', 'Penjualan Obat Racik Dari Transaksi PJL220230928', 384000, 1, '2023-09-28 18:16:49', NULL, '2023-09-28 18:16:49');
INSERT INTO `log` VALUES (127, 1, 'PMB120230929', '2023-09-29 22:10:11', 'Pembelian Barang Dari Transaksi PMB120230929', 650000, 1, '2023-09-29 22:10:11', NULL, '2023-09-29 22:10:11');
INSERT INTO `log` VALUES (128, 1, 'PJL220230929', '2023-09-29 22:10:58', 'Penjualan Obat Racik Dari Transaksi PJL220230929', 96000, 1, '2023-09-29 22:10:58', NULL, '2023-09-29 22:10:58');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

-- ----------------------------
-- Table structure for obat_racik
-- ----------------------------
DROP TABLE IF EXISTS `obat_racik`;
CREATE TABLE `obat_racik`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_racik` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `harga` int NOT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of obat_racik
-- ----------------------------
INSERT INTO `obat_racik` VALUES (11, 'Racik 001', 96000, 1, '2023-09-28 18:06:37', NULL, '2023-09-28 18:06:37');

-- ----------------------------
-- Table structure for pasien
-- ----------------------------
DROP TABLE IF EXISTS `pasien`;
CREATE TABLE `pasien`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_pasien` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_telp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  INDEX `id`(`id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pasien
-- ----------------------------
INSERT INTO `pasien` VALUES (1, 'Pasien1', '085172204869', 1, '2023-05-22 14:49:42', NULL, NULL);
INSERT INTO `pasien` VALUES (3, 'Pasien2', '0851213123', 1, '2023-06-02 16:02:24', NULL, '2023-06-02 16:02:24');

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for pembelian
-- ----------------------------
DROP TABLE IF EXISTS `pembelian`;
CREATE TABLE `pembelian`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NULL DEFAULT NULL,
  `id_barang` int NULL DEFAULT NULL,
  `id_supplier` int NOT NULL,
  `no_faktur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tgl_faktur` date NOT NULL,
  `status` int NOT NULL COMMENT '1 = tunai | 2 = tempo',
  `tgl_tempo` date NULL DEFAULT NULL,
  `jumlah` int NOT NULL,
  `jumlah_grosir` int NOT NULL,
  `total` int NOT NULL,
  `potongan` int NULL DEFAULT NULL,
  `tgl_lunas` date NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 68 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pembelian
-- ----------------------------
INSERT INTO `pembelian` VALUES (67, 457, 137, 7, 'f0001', '2023-09-30', 1, NULL, 24, 2, 650000, NULL, NULL, 1, '2023-09-29 22:10:11', NULL, '2023-09-29 22:10:11');

-- ----------------------------
-- Table structure for penjualan
-- ----------------------------
DROP TABLE IF EXISTS `penjualan`;
CREATE TABLE `penjualan`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_barang` int NOT NULL,
  `id_transaksi` int NULL DEFAULT NULL,
  `dokter_id` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga` int NOT NULL,
  `tipe` char(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0' COMMENT '0 = satuan | 1 = grosir | 2 = obat racik',
  `subtotal` int NOT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `dokter_id`(`dokter_id` ASC) USING BTREE,
  INDEX `barang_id`(`id_barang` ASC) USING BTREE,
  CONSTRAINT `barang_id_foreign` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `dokter_id_foreign` FOREIGN KEY (`dokter_id`) REFERENCES `dokter` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 145 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of penjualan
-- ----------------------------
INSERT INTO `penjualan` VALUES (142, 2, 442, 3, 5, 4550, '0', 22750, 1, '2023-09-22 11:33:41', NULL, '2023-09-22 11:33:41');
INSERT INTO `penjualan` VALUES (143, 11, 446, 3, 4, 96000, '2', 384000, 1, '2023-09-28 18:16:49', NULL, '2023-09-28 18:16:49');
INSERT INTO `penjualan` VALUES (144, 11, 460, 3, 1, 96000, '2', 96000, 1, '2023-09-29 22:10:58', NULL, '2023-09-29 22:10:58');

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for racik_barang
-- ----------------------------
DROP TABLE IF EXISTS `racik_barang`;
CREATE TABLE `racik_barang`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_racik` int NOT NULL,
  `id_barang` int NOT NULL,
  `jumlah` int NOT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of racik_barang
-- ----------------------------
INSERT INTO `racik_barang` VALUES (22, 11, 137, 21, NULL, '2023-09-28 18:06:37');
INSERT INTO `racik_barang` VALUES (23, 11, 138, 4, NULL, '2023-09-28 18:06:37');

-- ----------------------------
-- Table structure for supplier
-- ----------------------------
DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_supplier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kota` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_telp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of supplier
-- ----------------------------
INSERT INTO `supplier` VALUES (7, 'test supplier 1', 'surabaya', '0851123123', 'Jln. A. Yani No. 27', 1, '2023-05-10 10:14:07', 1, '2023-05-10 10:14:07');

-- ----------------------------
-- Table structure for transaksi
-- ----------------------------
DROP TABLE IF EXISTS `transaksi`;
CREATE TABLE `transaksi`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_akun` int NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `debt` int NULL DEFAULT NULL,
  `kredit` int NULL DEFAULT NULL,
  `potongan` int NULL DEFAULT NULL,
  `tanggal` date NOT NULL,
  `type` int NOT NULL COMMENT '0 = batal | 1 = pembelian | 2 = penjualan | 3 = kas | 4 = biaya | 5 = Lain-lain',
  `created_by` int NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 463 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of transaksi
-- ----------------------------
INSERT INTO `transaksi` VALUES (441, 112, 'Kas Bank', 'PJL120230922', 22750, NULL, NULL, '2023-09-22', 3, 1, '2023-09-22 11:33:41', NULL, '2023-09-22 11:33:41');
INSERT INTO `transaksi` VALUES (442, 411, 'Penjualan Barang', 'PJL120230922', NULL, 22750, NULL, '2023-09-22', 2, 1, '2023-09-22 11:33:41', NULL, '2023-09-22 11:33:41');
INSERT INTO `transaksi` VALUES (443, 119, 'HPP', 'PJL120230922', 7500, NULL, NULL, '2023-09-22', 5, 1, '2023-09-22 11:33:41', NULL, '2023-09-22 11:33:41');
INSERT INTO `transaksi` VALUES (444, 113, 'Persediaan Barang', 'PJL120230922', NULL, 7500, NULL, '2023-09-22', 5, 1, '2023-09-22 11:33:41', NULL, '2023-09-22 11:33:41');
INSERT INTO `transaksi` VALUES (445, 111, 'Kas', 'PJL220230928', 384000, NULL, NULL, '2023-09-28', 3, 1, '2023-09-28 18:16:49', NULL, '2023-09-28 18:16:49');
INSERT INTO `transaksi` VALUES (446, 411, 'Penjualan Obat Racik', 'PJL220230928', NULL, 384000, NULL, '2023-09-28', 2, 1, '2023-09-28 18:16:49', NULL, '2023-09-28 18:16:49');
INSERT INTO `transaksi` VALUES (447, 119, 'HPP', 'PJL220230928', 384000, NULL, NULL, '2023-09-28', 5, 1, '2023-09-28 18:16:49', NULL, '2023-09-28 18:16:49');
INSERT INTO `transaksi` VALUES (448, 111, 'Persediaan Barang', 'PJL220230928', NULL, 384000, NULL, '2023-09-28', 5, 1, '2023-09-28 18:16:49', NULL, '2023-09-28 18:16:49');
INSERT INTO `transaksi` VALUES (449, 111, 'Kas', 'BYA020230928', 10000, NULL, NULL, '2023-09-28', 4, 1, '2023-09-28 22:58:51', NULL, '2023-09-28 22:58:51');
INSERT INTO `transaksi` VALUES (450, 611, 'Biaya Listrik', 'BYA020230928', NULL, 10000, NULL, '2023-09-28', 3, 1, '2023-09-28 22:58:51', NULL, '2023-09-28 22:58:51');
INSERT INTO `transaksi` VALUES (451, 111, 'Kas', 'BYA15', 5000, NULL, NULL, '2023-09-28', 4, 1, '2023-09-28 23:00:13', NULL, '2023-09-28 23:00:13');
INSERT INTO `transaksi` VALUES (452, 612, 'Biaya air', 'BYA15', NULL, 5000, NULL, '2023-09-28', 3, 1, '2023-09-28 23:00:13', NULL, '2023-09-28 23:00:13');
INSERT INTO `transaksi` VALUES (453, 111, 'Kas', 'BYA16', 2000, NULL, NULL, '2023-09-29', 4, 1, '2023-09-29 20:50:52', NULL, '2023-09-29 20:50:52');
INSERT INTO `transaksi` VALUES (454, 611, 'Biaya Listrik', 'BYA16', NULL, 2000, NULL, '2023-09-29', 3, 1, '2023-09-29 20:50:52', NULL, '2023-09-29 20:50:52');
INSERT INTO `transaksi` VALUES (455, 111, 'Kas', 'BYA17', NULL, 2000, NULL, '2023-09-29', 4, 1, '2023-09-29 20:52:30', NULL, '2023-09-29 20:52:30');
INSERT INTO `transaksi` VALUES (456, 611, 'Biaya Listrik', 'BYA17', 2000, NULL, NULL, '2023-09-29', 3, 1, '2023-09-29 20:52:30', NULL, '2023-09-29 20:52:30');
INSERT INTO `transaksi` VALUES (457, 113, 'Persediaan Barang', 'PMB120230929', 650000, NULL, NULL, '2023-09-29', 1, 1, '2023-09-29 22:10:11', NULL, '2023-09-29 22:10:11');
INSERT INTO `transaksi` VALUES (458, 111, 'Kas', 'PMB120230929', NULL, 650000, NULL, '2023-09-29', 3, 1, '2023-09-29 22:10:11', NULL, '2023-09-29 22:10:11');
INSERT INTO `transaksi` VALUES (459, 111, 'Kas', 'PJL220230929', 96000, NULL, 0, '2023-09-29', 3, 1, '2023-09-29 22:10:58', NULL, '2023-09-29 22:10:58');
INSERT INTO `transaksi` VALUES (460, 411, 'Penjualan Obat Racik', 'PJL220230929', NULL, 96000, 0, '2023-09-29', 2, 1, '2023-09-29 22:10:58', NULL, '2023-09-29 22:10:58');
INSERT INTO `transaksi` VALUES (461, 119, 'HPP', 'PJL220230929', 96000, NULL, 0, '2023-09-29', 5, 1, '2023-09-29 22:10:58', NULL, '2023-09-29 22:10:58');
INSERT INTO `transaksi` VALUES (462, 111, 'Persediaan Barang', 'PJL220230929', NULL, 96000, 0, '2023-09-29', 5, 1, '2023-09-29 22:10:58', NULL, '2023-09-29 22:10:58');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'superadmin', 'Superadmin', 'admin@gmail.com', NULL, '$2y$10$Qh4xbCrCbEwpV/QIGxfI6ustTx/w2R3f1aC.p2zCOym2DsUpBcwNO', NULL, NULL, '2023-05-03 09:02:17', NULL, '2023-05-03 09:02:21');
INSERT INTO `users` VALUES (2, 'kasir', 'Kasir', 'kasir@gmail.com', NULL, '$2y$10$Qh4xbCrCbEwpV/QIGxfI6ustTx/w2R3f1aC.p2zCOym2DsUpBcwNO', NULL, NULL, '2023-05-03 09:03:54', NULL, '2023-05-03 09:03:58');
INSERT INTO `users` VALUES (8, 'kasir', 'kasir2', 'kasir2@gmail.com', NULL, '$2y$10$C/AdwffietTXyO9dWjEdBOSBaS9yVrRByxXUh1fAQICeMrnCcm3Ka', NULL, 1, '2023-07-12 09:56:41', NULL, '2023-07-12 09:56:41');
INSERT INTO `users` VALUES (9, 'pembelian', 'Pembelian', 'pembelian@gmail.com', NULL, '$2y$10$Ez.M8QUP6n/0Q2.ezhhvouN2FmxQVrYfcbGNFIGeDqbxLGog.DGm6', NULL, 1, '2023-07-18 02:36:42', NULL, '2023-07-18 02:36:42');

SET FOREIGN_KEY_CHECKS = 1;
