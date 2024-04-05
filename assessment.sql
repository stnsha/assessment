-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for assessment
CREATE DATABASE IF NOT EXISTS `assessment` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `assessment`;

-- Dumping structure for table assessment.brand
CREATE TABLE IF NOT EXISTS `brand` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table assessment.brand: ~5 rows (approximately)
INSERT INTO `brand` (`id`, `name`) VALUES
	(1, 'Samsung'),
	(2, 'Apple'),
	(3, 'Honor'),
	(4, 'Xiomi'),
	(5, 'OnePlus');

-- Dumping structure for table assessment.model
CREATE TABLE IF NOT EXISTS `model` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `brand_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table assessment.model: ~20 rows (approximately)
INSERT INTO `model` (`id`, `name`, `brand_id`) VALUES
	(1, 'Galaxy A55', 1),
	(2, 'Galaxy S24 Ultra', 1),
	(3, 'Galaxy Note 10+', 1),
	(4, 'Galaxy Tab A9+', 1),
	(5, 'iPhone 15 Pro Max', 2),
	(6, 'iPhone 11', 2),
	(7, 'iPhone XS Max', 2),
	(8, 'iPhone 13', 2),
	(9, 'X7b 5G', 3),
	(10, 'Magic6 Ultimate', 3),
	(11, 'X50 Pro', 3),
	(12, 'Pad 9', 3),
	(13, 'Poco C61', 4),
	(14, 'Civi 4 Pro', 4),
	(15, '14', 4),
	(16, 'Pad 6s Pro 12.4', 4),
	(17, 'Redmi A3', 4),
	(18, 'Ace 3V', 5),
	(19, 'Nord CE4', 5),
	(20, '12R', 5);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
