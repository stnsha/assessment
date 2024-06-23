/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `brand`;
CREATE TABLE `brand` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `model`;
CREATE TABLE `model` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `brand_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `user_brands`;
CREATE TABLE `user_brands` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `brand_id` int NOT NULL,
  `model_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_brands_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `user_phones`;
CREATE TABLE `user_phones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `phone_no` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_phones_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ic_no` varchar(50) NOT NULL,
  `age` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `brand` (`id`, `name`) VALUES
(1, 'Samsung');
INSERT INTO `brand` (`id`, `name`) VALUES
(2, 'Apple');
INSERT INTO `brand` (`id`, `name`) VALUES
(3, 'Honor');
INSERT INTO `brand` (`id`, `name`) VALUES
(4, 'Xiomi'),
(5, 'OnePlus');

INSERT INTO `model` (`id`, `name`, `brand_id`) VALUES
(1, 'Galaxy A55', 1);
INSERT INTO `model` (`id`, `name`, `brand_id`) VALUES
(2, 'Galaxy S24 Ultra', 1);
INSERT INTO `model` (`id`, `name`, `brand_id`) VALUES
(3, 'Galaxy Note 10+', 1);
INSERT INTO `model` (`id`, `name`, `brand_id`) VALUES
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

INSERT INTO `user_brands` (`id`, `user_id`, `brand_id`, `model_id`, `created_at`) VALUES
(1, 1, 2, 6, '2024-06-22 00:33:54');
INSERT INTO `user_brands` (`id`, `user_id`, `brand_id`, `model_id`, `created_at`) VALUES
(2, 2, 1, 3, '2024-06-22 00:39:01');
INSERT INTO `user_brands` (`id`, `user_id`, `brand_id`, `model_id`, `created_at`) VALUES
(3, 3, 3, 9, '2024-06-22 00:39:22');
INSERT INTO `user_brands` (`id`, `user_id`, `brand_id`, `model_id`, `created_at`) VALUES
(4, 4, 3, 10, '2024-06-22 00:41:31'),
(5, 5, 2, 6, '2024-06-22 00:41:58');

INSERT INTO `user_phones` (`id`, `user_id`, `phone_no`, `created_at`) VALUES
(2, 1, '01156403061', '2024-06-22 00:38:31');
INSERT INTO `user_phones` (`id`, `user_id`, `phone_no`, `created_at`) VALUES
(3, 1, '0123456789', '2024-06-22 00:38:31');
INSERT INTO `user_phones` (`id`, `user_id`, `phone_no`, `created_at`) VALUES
(4, 2, '01156403061', '2024-06-22 00:39:01');
INSERT INTO `user_phones` (`id`, `user_id`, `phone_no`, `created_at`) VALUES
(5, 3, '01156403061', '2024-06-22 00:39:22'),
(6, 4, '01156403061', '2024-06-22 00:41:31'),
(8, 5, '01156403061', '2024-06-22 00:42:15');

INSERT INTO `users` (`id`, `name`, `email`, `ic_no`, `age`, `created_at`) VALUES
(1, 'Siti Anasuha', 'anasuharosli@gmail.com', '971202-05-5558', 27, '2024-06-22 00:33:54');
INSERT INTO `users` (`id`, `name`, `email`, `ic_no`, `age`, `created_at`) VALUES
(2, 'Siti Anasuha', 'anasurosli@gmail.com', '971202-05-5555', 27, '2024-06-22 00:39:01');
INSERT INTO `users` (`id`, `name`, `email`, `ic_no`, `age`, `created_at`) VALUES
(3, 'Siti Anasuha', 'anasuharaosli@gmail.com', '971202-05-5552', 27, '2024-06-22 00:39:22');
INSERT INTO `users` (`id`, `name`, `email`, `ic_no`, `age`, `created_at`) VALUES
(4, 'Siti Anasuha', 'joenwnou@gmail.com', '971202-05-5550', 27, '2024-06-22 00:41:31'),
(5, 'SITI ANASUHA', 'sityanasuha829@gmail.com', '971202-05-5553', 27, '2024-06-22 00:41:58');


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;