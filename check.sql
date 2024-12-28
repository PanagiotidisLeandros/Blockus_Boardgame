-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for adise24_blokus
CREATE DATABASE IF NOT EXISTS `adise24_blokus` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `adise24_blokus`;

-- Dumping structure for table adise24_blokus.checker
CREATE TABLE IF NOT EXISTS `checker` (
  `x` tinyint(5) NOT NULL,
  `y` tinyint(5) NOT NULL,
  `cell_status` enum('E','R','B') NOT NULL DEFAULT 'E',
  PRIMARY KEY (`x`,`y`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adise24_blokus.checker: ~25 rows (approximately)
INSERT INTO `checker` (`x`, `y`, `cell_status`) VALUES
	(1, 1, 'E'),
	(1, 2, 'E'),
	(1, 3, 'E'),
	(1, 4, 'E'),
	(1, 5, 'E'),
	(2, 1, 'E'),
	(2, 2, 'E'),
	(2, 3, 'E'),
	(2, 4, 'E'),
	(2, 5, 'E'),
	(3, 1, 'E'),
	(3, 2, 'E'),
	(3, 3, 'E'),
	(3, 4, 'E'),
	(3, 5, 'E'),
	(4, 1, 'E'),
	(4, 2, 'E'),
	(4, 3, 'E'),
	(4, 4, 'E'),
	(4, 5, 'E'),
	(5, 1, 'E'),
	(5, 2, 'E'),
	(5, 3, 'E'),
	(5, 4, 'E'),
	(5, 5, 'E');
	
-- Dumping structure for procedure adise24_blokus.clean_checker
DELIMITER //
CREATE PROCEDURE `clean_checker`()
BEGIN
	REPLACE INTO checker SELECT * FROM clean_checker;
END//
DELIMITER ;

-- Dumping structure for table adise24_blokus.clean_checker
CREATE TABLE IF NOT EXISTS `clean_checker` (
  `x` tinyint(5) NOT NULL,
  `y` tinyint(5) NOT NULL,
  `cell_status` enum('E','R','B') NOT NULL DEFAULT 'E',
  `legal_move` enum('Y','N') DEFAULT NULL,
  PRIMARY KEY (`x`,`y`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table adise24_blokus.clean_checker: ~25 rows (approximately)
INSERT INTO `clean_checker` (`x`, `y`, `cell_status`, `legal_move`) VALUES
	(1, 1, 'E', NULL),
	(1, 2, 'E', NULL),
	(1, 3, 'E', NULL),
	(1, 4, 'E', NULL),
	(1, 5, 'E', NULL),
	(1, 6, 'E', NULL),
	(2, 1, 'E', NULL),
	(2, 2, 'E', NULL),
	(2, 3, 'E', NULL),
	(2, 4, 'E', NULL),
	(2, 5, 'E', NULL),
	(3, 1, 'E', NULL),
	(3, 2, 'E', NULL),
	(3, 3, 'E', NULL),
	(3, 4, 'E', NULL),
	(3, 5, 'E', NULL),
	(4, 1, 'E', NULL),
	(4, 2, 'E', NULL),
	(4, 3, 'E', NULL),
	(4, 4, 'E', NULL),
	(4, 5, 'E', NULL),
	(5, 1, 'E', NULL),
	(5, 2, 'E', NULL),
	(5, 3, 'E', NULL),
	(5, 4, 'E', NULL),
	(5, 5, 'E', NULL);


/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
